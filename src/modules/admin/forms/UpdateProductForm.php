<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;

class UpdateProductForm extends Product
{
    public $name;

    public $image_id;

    public $price;

    public $text;

    public $url_alias;

    public $category_id;

    public $is_prices_extended;

    public function rules()
    {
        return [
            [['name'], 'required'],
            ['price', 'integer'],
            ['is_prices_extended', 'integer'],
            [['category_id'], 'required'],
            [['text'], 'string'],
            [['url_alias'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'category_id' => 'Категории'
        ]);
    }

}