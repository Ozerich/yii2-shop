<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;
use ozerich\shop\traits\ServicesTrait;
use yii\base\Model;

class ProductSeoFormConvertor extends Model
{
    use ServicesTrait;

    public function loadFormFromModel(Product $product)
    {
        $form = new ProductSeoForm();

        $form->h1_value = $product->h1_value;
        $form->seo_title = $product->seo_title;
        $form->seo_description = $product->seo_description;

        return $form;
    }

    public function saveModelFromForm(Product $model, ProductSeoForm $form)
    {
        $model->h1_value = $form->h1_value;
        $model->seo_title = $form->seo_title;
        $model->seo_description = $form->seo_description;

        $model->save(false, ['h1_value', 'seo_title', 'seo_description']);

        return true;
    }
}