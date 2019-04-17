<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;

class UpdateProductForm extends Product
{
    public $name;

    public $image_id;

    public $manufacture_id;

    public $schema_image_id;

    public $price;

    public $text;

    public $url_alias;

    public $category_id;

    public $is_prices_extended;

    public $sku;

    public $sale_disabled;

    public $sale_disabled_text;

    public function rules()
    {
        return [
            [['name'], 'required'],
            ['price', 'integer'],
            [['is_prices_extended', 'sale_disabled', 'manufacture_id'], 'integer'],
            [['category_id'], 'required'],
            [['text', 'sku', 'sale_disabled_text'], 'string'],
            [['url_alias'], 'string', 'max' => 100],
            [['image_id', 'schema_image_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'category_id' => 'Категории'
        ]);
    }

}