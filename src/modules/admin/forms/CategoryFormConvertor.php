<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Category;
use ozerich\shop\models\CategoryField;
use ozerich\shop\traits\ServicesTrait;
use yii\base\Model;

class CategoryFormConvertor extends Model
{
    use ServicesTrait;

    public function loadFormFromModel(Category $category)
    {
        $form = new CategoryForm();

        $form->name = $category->name;
        $form->parent_id = $category->parent_id;
        $form->url_alias = $category->url_alias;
        $form->image_id = $category->image_id;
        $form->text = $category->text;

        $form->field_ids = CategoryField::find()
            ->andWhere('category_id=:category_id', [':category_id' => $category->id])
            ->select('field_id')
            ->column();

        return $form;
    }

    public function saveModelFromForm(Category $model, CategoryForm $form)
    {
        $model->name = $form->name;
        $model->parent_id = $form->parent_id;
        $model->url_alias = $form->url_alias;
        $model->text = $form->text;
        $model->image_id = $form->image_id;

        $model->save();

        $this->categoriesService()->updateCategoryLevel($model);

        CategoryField::deleteAll(['category_id' => $model->id]);

        if ($form->field_ids) {
            foreach ($form->field_ids as $field) {
                $fieldModel = new CategoryField();

                $fieldModel->field_id = $field;
                $fieldModel->category_id = $model->id;

                $fieldModel->save();
            }
        }

        return true;
    }
}