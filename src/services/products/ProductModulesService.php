<?php

namespace ozerich\shop\services\products;

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


        return true;
    }

    public function deleteModule(ProductModule $module)
    {
        $module->delete();
    }

    public function setQuantity(ProductModule $module, $value)
    {
        $module->default_quantity = $value;
        $module->save(false, ['default_quantity']);
    }
}