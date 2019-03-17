<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Category;

class CategoryForm extends Category
{
    public $name;

    public $url_alias;

    public $parent_id;

    public $image_id;

    public $field_ids;

    public $text;

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'field_ids' => 'Включенные поля'
        ]);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['field_ids', 'safe']
        ]);
    }
}