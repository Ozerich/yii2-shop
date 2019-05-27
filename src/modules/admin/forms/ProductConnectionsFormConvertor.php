<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;
use ozerich\shop\models\ProductCategory;
use ozerich\shop\models\ProductSame;
use ozerich\shop\traits\ServicesTrait;

class ProductConnectionsFormConvertor
{
    use ServicesTrait;

    public function loadFormFromModel(Product $product)
    {
        $form = new ProductConnectionsForm();

        $form->collection_id = $product->collection_id;
        $form->manufacture_id = $product->manufacture_id;
        $form->category_id = $product->category_id;
        $form->same = $product->sameProducts;

        return $form;
    }

    public function saveModelFromForm(Product $product, ProductConnectionsForm $form)
    {
        $manufactureChanged = false;

        if ($product->manufacture_id != $form->manufacture_id) {
            $manufactureChanged = true;
        }

        $product->collection_id = $form->collection_id;
        $product->manufacture_id = $form->manufacture_id;
        $product->save(false, ['collection_id', 'manufacture_id']);

        if ($product->category_id != $form->category_id) {
            ProductCategory::deleteAll(['product_id' => $product->id, 'category_id' => $form->category_id]);
            $item = new ProductCategory();
            $item->product_id = $product->id;
            $item->category_id = $form->category_id;
            $item->save();

            $product->category_id = $form->category_id;
            $product->save(false, ['category_id']);

            $this->categoryManufacturesService()->onUpdateCategory($product->category_id);
            $this->categoryManufacturesService()->onUpdateCategory($form->category_id);
        }

        if ($manufactureChanged) {
            $this->categoryManufacturesService()->onUpdateCategory($product->category_id);
        }

        ProductSame::deleteAll('product_id=:product_id OR product_same_id=:product_id', [':product_id' => $product->id]);

        foreach ($form->same as $productId) {
            $model = new ProductSame();
            $model->product_id = $product->id;
            $model->product_same_id = $productId;
            $model->save();

            $model = new ProductSame();
            $model->product_id = $productId;
            $model->product_same_id = $product->id;
            $model->save();
        }

        return $model;
    }
}