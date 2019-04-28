<?php

namespace ozerich\shop\services\products;

use ozerich\shop\models\Product;
use ozerich\shop\models\ProductCategory;
use ozerich\shop\models\ProductFieldValue;
use ozerich\shop\models\ProductImage;
use ozerich\shop\models\ProductPrice;
use ozerich\shop\models\ProductPriceParam;
use ozerich\shop\models\ProductPriceParamValue;
use ozerich\shop\traits\ServicesTrait;

class ProductBaseService
{
    use ServicesTrait;

    private function cloneModel($className, $original)
    {
        $model = new $className;
        foreach ($original->attributes as $attribute => $val) {
            $model->{$attribute} = $val;
        }

        try {
            $model->id = null;
        } catch (\yii\base\Exception $exception) {

        }

        try {
            $model->save();
        } catch (\yii\base\Exception $exception) {

        }

        return $model;
    }

    public function createCopy(Product $product)
    {
        $model = $this->cloneModel(Product::class, $product);

        $name = $model->name;
        $index = 1;
        while (true) {
            $name = $model->name . ' - Копия' . ($index > 1 ? ' ' . $index : '');

            $exists = Product::find()
                ->andWhere('name=:name', [':name' => $name])
                ->andWhere('category_id=:category_id', [':category_id' => $product->category_id])
                ->exists();

            if (!$exists) {
                break;
            }

            $index++;
        }
        $model->name = $name;
        $model->save(false, ['name']);

        foreach ($product->productCategories as $value) {
            $productFieldValue = $this->cloneModel(ProductCategory::class, $value);
            $productFieldValue->product_id = $model->id;
            $productFieldValue->save();
        }

        foreach ($product->productFieldValues as $value) {
            $productFieldValue = $this->cloneModel(ProductFieldValue::class, $value);
            $productFieldValue->product_id = $model->id;
            $productFieldValue->save();
        }

        foreach ($product->productImages as $value) {
            $productImage = $this->cloneModel(ProductImage::class, $value);
            $productImage->product_id = $model->id;
            $productImage->save();
        }

        $priceParamsMap = [];
        $priceParamValuesMap = [];
        foreach ($product->productPriceParams as $value) {
            $productPriceParam = $this->cloneModel(ProductPriceParam::class, $value);
            $productPriceParam->product_id = $model->id;
            $productPriceParam->save();

            $priceParamsMap[$value->id] = $productPriceParam->id;

            foreach ($value->productPriceParamValues as $productPriceParamValue) {
                $newPriceParamValue = $this->cloneModel(ProductPriceParamValue::class, $productPriceParamValue);
                $newPriceParamValue->product_price_param_id = $productPriceParam->id;
                $newPriceParamValue->save();

                $priceParamValuesMap[$productPriceParamValue->id] = $newPriceParamValue->id;
            }
        }

        foreach ($product->prices as $priceModel) {
            $newPriceModel = $this->cloneModel(ProductPrice::class, $priceModel);

            if ($priceModel->param_value_id) {
                $newPriceModel->param_value_id = isset($priceParamValuesMap[$priceModel->param_value_id]) ? $priceParamValuesMap[$priceModel->param_value_id] : null;
            }

            if ($priceModel->param_value_second_id) {
                $newPriceModel->param_value_second_id = isset($priceParamValuesMap[$priceModel->param_value_second_id]) ? $priceParamValuesMap[$priceModel->param_value_second_id] : null;
            }

            $newPriceModel->product_id = $model->id;
            $newPriceModel->save();
        }

        return $model;
    }
}