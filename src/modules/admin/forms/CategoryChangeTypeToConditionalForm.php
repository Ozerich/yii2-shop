<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Category;

class CategoryChangeTypeToConditionalForm extends Category
{
    public $category_id;

    public function attributeLabels()
    {
        return [
            'category_id' => 'Категория, в которую перенести товары'
        ];
    }

    public function rules(){
        return [
            [['category_id'], 'required']
        ];
    }
}