<?php

namespace ozerich\shop\services\categories;

use ozerich\shop\models\Category;
use ozerich\shop\models\CategoryField;
use ozerich\shop\models\Field;
use ozerich\shop\traits\ServicesTrait;

class CategoryFieldsService
{
    use ServicesTrait;

    public function addFieldToCategory(Field $field, Category $category)
    {
        CategoryField::deleteAll([
            'field_id' => $field->id,
            'category_id' => $category->id
        ]);

        $model = new CategoryField();
        $model->field_id = $field->id;
        $model->category_id = $category->id;
        $model->save();
    }

    public function removeFieldFromCategory(Field $field, Category $category)
    {
        CategoryField::deleteAll([
            'field_id' => $field->id,
            'category_id' => $category->id
        ]);
    }

    public function isActiveFieldForCategory(Field $field, Category $category)
    {
        return CategoryField::find()->andWhere('field_id=:field_id AND category_id=:category_id', [
            ':field_id' => $field->id,
            ':category_id' => $category->id
        ])->exists();
    }

    public function getParentFieldsForCategoryQuery(Category $category)
    {
        $parent_ids = $this->categoriesService()->getParentIds($category);

        if (empty($parent_ids)) {
            return Field::find()->andWhere('0');
        }

        return Field::find()->andWhere('category_id IN (' . implode(',', $parent_ids) . ')');
    }

    public function getFieldsForCategoryQuery(Category $category)
    {
        return Field::find()->andWhere('category_id=:category_id', [':category_id' => $category->id]);
    }
}