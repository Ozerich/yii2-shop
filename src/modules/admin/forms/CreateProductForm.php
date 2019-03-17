<?php

namespace ozerich\shop\modules\admin\forms;

use yii\base\Model;

class CreateProductForm extends Model
{
    public $name;

    public $category_id;

    public $image_id;

    public function rules()
    {
        return [
            [['name', 'category_id'], 'required'],
            [['image_id'], 'integer'],
            [['name'], 'string', 'max' => 150]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'category_id' => 'Категория',
            'image_id' => 'Картинка'
        ];
    }
}