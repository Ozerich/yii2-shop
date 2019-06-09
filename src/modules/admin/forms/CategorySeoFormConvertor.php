<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\constants\CategoryType;
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
        $form->seo_description_products_template = $category->seo_description_products_template;
        $form->seo_title_products_template = $category->seo_title_products_template;

        return $form;
    }

    public function saveModelFromForm(Category $model, CategorySeoForm $form)
    {
        $model->h1_value = $form->h1_value;
        $model->seo_title = $form->seo_title;
        $model->seo_description = $form->seo_description;

        $model->save(false, ['h1_value', 'seo_title', 'seo_description']);

        if ($model->type == CategoryType::CATALOG) {
            $model->seo_title_products_template = $form->seo_title_products_template;
            $model->seo_description_products_template = $form->seo_description_products_template;
            $model->save(false, ['seo_title_products_template', 'seo_description_products_template']);
        }

        return true;
    }
}