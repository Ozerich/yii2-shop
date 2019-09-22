<?php

namespace ozerich\shop\import;

use ozerich\filestorage\FileStorage;
use ozerich\shop\constants\DiscountType;
use ozerich\shop\constants\Stock;
use ozerich\shop\models\Category;
use ozerich\shop\models\Field;
use ozerich\shop\models\Manufacture;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductImage;
use ozerich\shop\models\ProductPrice;
use ozerich\shop\models\ProductPriceParam;
use ozerich\shop\models\ProductPriceParamValue;
use ozerich\shop\modules\admin\Module;
use ozerich\shop\traits\ServicesTrait;
use ozerich\tools\utils\Translit;

class ImportProductService
{
    use ServicesTrait;

    /**
     * @return ImportProductStrategyInterface[]
     */
    private function strategies()
    {
        /** @var Module $module */
        $module = \Yii::$app->controller->module;

        if (!$module instanceof Module) {
            return [];
        }

        $classNames = $module->importProductStrategies;

        return array_map(function ($className) {
            return \Yii::createObject($className);
        }, $classNames);
    }

    /**
     * @return FileStorage
     */
    private function media()
    {
        return \Yii::$app->media;
    }

    private function getImageIdByUrl($url, $scenario = 'product')
    {
        if (empty($url)) {
            return null;
        }

        $model = $this->media()->createFileFromUrl($url, $scenario);
        return $model ? $model->id : null;
    }

    /**
     * @return array
     */
    public function allowedDomains()
    {
        $result = [];

        foreach ($this->strategies() as $strategy) {
            $result = array_merge($result, $strategy->domains());
        }

        return array_values(array_unique($result));
    }

    private function getDomainFromUrl($url)
    {
        $parse = parse_url($url);
        return $parse['host'];
    }

    private function getStrategyByUrl($url)
    {
        $domain = $this->getDomainFromUrl($url);

        foreach ($this->strategies() as $strategy) {
            if (in_array($domain, $strategy->domains())) {
                return $strategy;
            }
        }

        return null;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function validateUrl($url)
    {
        return in_array($this->getDomainFromUrl($url), $this->allowedDomains());
    }


    /**
     * @param ImportProductStrategyInterface $strategy
     * @return Manufacture|null
     */
    private function getManufactureByStrategy(ImportProductStrategyInterface $strategy)
    {
        $manufacture = $strategy->manufacture();
        if (!$manufacture) {
            return null;
        }

        return Manufacture::find()->andWhere('name=:name', [':name' => $manufacture])->one();
    }

    /**
     * @param string $url
     * @param Category $category
     * @return Product
     */
    public function import($url, Category $category)
    {
        $strategy = $this->getStrategyByUrl($url);
        if (!$strategy) {
            return null;
        }

        $importProduct = $strategy->import($url);
        if (!$importProduct) {
            return null;
        }

        $model = new Product();

        $model->hidden = true;
        $model->sku = $importProduct->getSku();
        $model->name = $importProduct->getName();
        $model->url_alias = Translit::convert($model->name);
        $model->image_id = $this->getImageIdByUrl($importProduct->getMainImageUrl());
        $model->schema_image_id = $this->getImageIdByUrl($importProduct->getSchema());
        $model->video = $importProduct->getVideo();
        $model->price = $importProduct->getPrice();

        $oldPrice = $importProduct->getOldPrice();
        if (!empty($oldPrice)) {
            $model->price_with_discount = $model->price;
            $model->price = $oldPrice;
            $model->discount_mode = DiscountType::AMOUNT;
            $model->discount_value = $model->price - $model->price_with_discount;
        }

        $priceParams = $importProduct->getPriceParams();
        if (!empty($priceParams)) {
            $model->is_prices_extended = 1;
        }

        if (!$model->save()) {
            return null;
        }

        foreach ($importProduct->getParams() as $param => $value) {

            /** @var Field $field */
            $field = Field::find()->andWhere('name=:name', [':name' => $param])
                ->andWhere('category_id=:category_id', [':category_id' => $category->id])
                ->one();

            if (!$field && $category->parent_id) {
                $field = Field::find()->andWhere('name=:name', [':name' => $param])
                    ->andWhere('category_id=:category_id', [':category_id' => $category->parent_id])
                    ->one();
            }

            if (!$field) {
                continue;
            }

            $this->productFieldsService()->setProductFieldValue($model, $field, $value);
        }


        foreach ($importProduct->getImages() as $image) {
            $productImage = new ProductImage();
            $productImage->product_id = $model->id;
            $productImage->image_id = $this->getImageIdByUrl($image['url']);
            $productImage->text = $image['description'];
            $productImage->save();
        }

        foreach ($importProduct->getPriceParams() as $priceParam) {
            $priceParamModel = new ProductPriceParam();
            $priceParamModel->product_id = $model->id;
            $priceParamModel->name = $priceParam['label'];
            if (!$priceParamModel->save()) {
                continue;
            }

            foreach ($priceParam['options'] as $option) {
                $priceParamOption = new ProductPriceParamValue();
                $priceParamOption->product_price_param_id = $priceParamModel->id;
                $priceParamOption->name = is_string($option) ? $option : $option['label'];
                $priceParamOption->description = is_string($option) ? null : $option['description'];
                $priceParamOption->save();
            }
        }

        foreach ($importProduct->getExtendedPrices() as $price) {
            $params = $price['params'];

            if (count($params) != 1) {
                continue;
            }

            $keys = array_keys($params);
            $firstParamLabel = array_shift($keys);

            $values = array_values($params);
            $firstParamValueLabel = array_shift($values);

            $priceParam = ProductPriceParam::find()->andWhere('product_id=:product_id', [':product_id' => $model->id])
                ->andWhere('name=:name', [':name' => $firstParamLabel])->one();
            if (!$priceParam) {
                continue;
            }

            $priceParamValue = ProductPriceParamValue::find()->andWhere('product_price_param_id=:param_id', [':param_id' => $priceParam->id])
                ->andWhere('name=:name', [':name' => $firstParamValueLabel])->one();
            if (!$priceParamValue) {
                continue;
            }

            $priceModel = new ProductPrice();

            $priceModel->product_id = $model->id;
            $priceModel->param_value_id = $priceParamValue->id;
            $priceModel->value = $price['price'];
            $priceModel->stock = Stock::WAITING;

            if (!empty($price['oldPrice'])) {
                $priceModel->discount_value = $price['price'] - $price['oldPrice'];
                $priceModel->discount_mode = DiscountType::AMOUNT;
                $priceModel->value = $price['oldPrice'];
            }

            $priceModel->save();
        }

        $manufacture = $this->getManufactureByStrategy($strategy);
        $this->categoryManufacturesService()->setProductManufacture($model, $manufacture);

        if ($category) {
            $model = $this->categoryProductsService()->setProductCategory($model, $category);
        }

        $this->categoryProductsService()->afterProductParamsChanged($model);

        return $model;
    }
}