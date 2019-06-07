<?php

namespace ozerich\shop\services\products;

use ozerich\shop\constants\ProductType;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductModule;

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

    public function createModule(Product $product, $name, $sku, $comment, $price, $discountMode, $discountValue)
    {
        $model = new ProductModule();

        $model->product_id = $product->id;
        $model->name = $name;
        $model->sku = $sku;
        $model->note = $comment;
        $model->price = $price;
        $model->discount_mode = $discountMode;
        $model->discount_value = $discountValue;

        return $model->save();
    }

    public function deleteModule(ProductModule $module)
    {
        $module->delete();
    }
}