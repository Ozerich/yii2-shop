<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;
use yii\base\Model;

class UpdateProductFormConvertor extends Model
{
    public function loadFormFromModel(Product $product)
    {
        $form = new UpdateProductForm();

        $form->name = $product->name;
        $form->image_id = $product->image_id;
        $form->text = $product->text;
        $form->price = $product->price;
        $form->url_alias = $product->url_alias;
        $form->category_id = $product->category_id;
        $form->is_prices_extended = $product->is_prices_extended;

        return $form;
    }

    public function saveModelFromForm(Product $model, UpdateProductForm $form)
    {
        $model->name = $form->name;
        $model->image_id = $form->image_id;
        $model->text = $form->text;
        $model->price = $form->price;
        $model->url_alias = $form->url_alias;
        $model->category_id = $form->category_id;
        $model->is_prices_extended = $form->is_prices_extended;

        return $model->save();
    }


}