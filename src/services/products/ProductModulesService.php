<?php

namespace ozerich\shop\services\products;

use ozerich\shop\constants\DiscountType;
use ozerich\shop\constants\ProductType;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductModule;
use ozerich\shop\models\ProductModuleImage;

class ProductModulesService
{
    /**
     * @param $id
     * @return Product
     */
    public function getModuleProductById($id)
    {
        return Product::find()->andWhere('type=:type', [':type' => ProductType::MODULAR])->andWhere('id=:id', [':id' => $id])->one();
    }

    /**
     * @param $id
     * @return ProductModule
     */
    public function getModuleById($id)
    {
        return ProductModule::findOne($id);
    }

    private function updatePriceDiscount(ProductModule $module)
    {
        if ($module->discount_mode) {
            switch ($module->discount_mode) {
                case DiscountType::AMOUNT:
                    $value = $module->price - $module->discount_value;
                    break;
                case DiscountType::PERCENT:
                    $value = $module->price - ($module->price / 100 * min(100, $module->discount_value));
                    break;
                case DiscountType::FIXED:
                    $value = $module->discount_value;
                    break;
                default:
                    $value = $module->price;
                    break;
            }

            $module->price_with_discount = $value;
        }


        return $module;
    }

    public function createModule(Product $product, $name, $sku, $comment, $price, $discountMode, $discountValue, $imageIds, $params)
    {
        $model = new ProductModule();

        $model->product_id = $product->id;
        $model->name = $name;
        $model->sku = $sku;
        $model->note = $comment;
        $model->price = $price;
        $model->discount_mode = $discountMode;
        $model->discount_value = $discountValue;
        $model->params = $params ? json_encode($params) : null;

        $model = $this->updatePriceDiscount($model);

        if (!$model->save()) {
            return false;
        }

        if ($imageIds && is_array($imageIds)) {
            foreach ($imageIds as $ind => $imageId) {
                $imageModel = new ProductModuleImage();
                $imageModel->product_module_id = $model->id;
                $imageModel->image_id = $imageId;
                $imageModel->save();

                if ($ind == 0) {
                    $model->image_id = $imageId;
                    $model->save(false, ['image_id']);
                }
            }
        }


        $this->updateProductPrice($product);

        return true;
    }

    public function deleteModule(ProductModule $module)
    {
        $module->delete();

        $this->updateProductPrice($module->product);
    }

    public function setQuantity(ProductModule $module, $value)
    {
        $module->default_quantity = $value;
        $module->save(false, ['default_quantity']);

        $this->updateProductPrice($module->product);
    }

    public function updateProductPrice(Product $product)
    {
        /** @var ProductModule[] $modules */
        $modules = ProductModule::find()->andWhere('product_id=:product_id', [':product_id' => $product->id])->all();

        $price = 0;
        $discount = 0;

        foreach ($modules as $module) {
            if ($module->default_quantity == 0) {
                continue;
            }

            $price += $module->price * $module->default_quantity;
            if ($module->price_with_discount) {
                $discount += ($module->price - $module->price_with_discount) * $module->default_quantity;
            }
        }

        $product->price = $price ? $price : null;

        if ($discount) {
            $product->discount_mode = DiscountType::AMOUNT;
            $product->discount_value = $discount;
            $product->price_with_discount = $product->price - $discount;
        } else {
            $product->discount_value = null;
            $product->discount_mode = null;
            $product->price_with_discount = null;
        }

        $product->save(false, ['discount_mode', 'discount_value', 'price_with_discount', 'price']);
    }
}