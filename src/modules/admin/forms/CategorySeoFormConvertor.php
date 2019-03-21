<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Category;
use ozerich\shop\traits\ServicesTrait;
use yii\base\Model;

class CategorySeoFormConvertor extends Model
{
    use ServicesTrait;

    public function loadFormFromModel(Category $category)
    {
        $form = new CategorySeoForm();

        $form->h1_value = $category->h1_value;
        $form->seo_title = $category->seo_title;
        $form->seo_description = $category->seo_description;

        return $form;
    }

    public function saveModelFromForm(Category $model, CategorySeoForm $form)
    {
        $model->h1_value = $form->h1_value;
        $model->seo_title = $form->seo_title;
        $model->seo_description = $form->seo_description;

        $model->save(false, ['h1_value', 'seo_title', 'seo_description']);

        return true;
    }
}