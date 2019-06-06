<?php

namespace ozerich\shop\modules\admin\forms;

use yii\base\Model;

class CreateProductForm extends Model
{
    public $name;

    public $type;

    public $manufacture_id;

    public $category_id;

    public $image_id;

    public $sku;

    public $label;

    public function rules()
    {
        return [
            [['name', 'category_id', 'type'], 'required'],
            [['image_id', 'manufacture_id'], 'integer'],
            [['name', 'sku', 'label'], 'string', 'max' => 150],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'manufacture_id' => 'Производитель',
            'category_id' => 'Категория',
            'image_id' => 'Картинка',
            'sku' => 'Артикул',
            'label' => 'Маркировка',
            'type' => 'Тип товара'
        ];
    }
}