<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;
use ozerich\shop\models\ProductCategory;
use ozerich\shop\traits\ServicesTrait;
use yii\base\Model;

class UpdateProductFormConvertor extends Model
{
    use ServicesTrait;

    public function loadFormFromModel(Product $product)
    {
        $form = new UpdateProductForm();

        $form->type = $product->type;
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
        $form->hidden = $product->hidden;
        $form->is_new = $product->is_new;
        $form->popular = $product->popular;
        $form->label = $product->label;

        return $form;
    }

    public function saveModelFromForm(Product $model, UpdateProductForm $form)
    {
        $manufactureChanged = false;

        if ($model->hidden != $form->hidden) {
            $manufactureChanged = true;
        }

        $model->type = $form->type;
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
        $model->hidden = $form->hidden;
        $model->is_new = $form->is_new;
        $model->popular = $form->popular;
        $model->label = $form->label;

        $model->save();

        if ($manufactureChanged) {
            $this->categoryManufacturesService()->onUpdateCategory($model->category_id);
        }

        return true;
    }


}