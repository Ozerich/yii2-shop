<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;
use ozerich\shop\models\ProductCategory;
use yii\base\Model;

class UpdateProductFormConvertor extends Model
{
    public function loadFormFromModel(Product $product)
    {
        $form = new UpdateProductForm();

        $form->name = $product->name;
        $form->image_id = $product->image_id;
        $form->schema_image_id = $product->schema_image_id;
        $form->text = $product->text;
        $form->price = $product->price;
        $form->url_alias = $product->url_alias;
        $form->is_prices_extended = $product->is_prices_extended;
        $form->sku = $product->sku;
        $form->sale_disabled = $product->sale_disabled;
        $form->sale_disabled_text = $product->sale_disabled_text;
        $form->category_id = $product->category_id;

        return $form;
    }

    public function saveModelFromForm(Product $model, UpdateProductForm $form)
    {
        $model->name = $form->name;
        $model->image_id = $form->image_id;
        $model->schema_image_id = $form->schema_image_id;
        $model->text = $form->text;
        $model->price = $form->price;
        $model->url_alias = $form->url_alias;
        $model->is_prices_extended = $form->is_prices_extended;
        $model->sku = $form->sku;
        $model->sale_disabled = $form->sale_disabled;
        $model->sale_disabled_text = $form->sale_disabled_text;

        if ($model->category_id != $form->category_id) {
            ProductCategory::deleteAll(['product_id' => $model->id, 'category_id' => $form->category_id]);
            $item = new ProductCategory();
            $item->product_id = $model->id;
            $item->category_id = $form->category_id;
            $item->save();
        }

        return $model->save();
    }


}