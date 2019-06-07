<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;

class UpdateProductForm extends Product
{
    public $name;

    public $image_id;

    public $schema_image_id;

    public $price;

    public $text;

    public $url_alias;

    public $is_prices_extended;

    public $sku;

    public $sale_disabled;

    public $sale_disabled_text;

    public $label;

    public $hidden;

    public $is_new;

    public $popular;

    public $type;

    public function rules()
    {
        return [
            [['name'], 'required'],
            ['price', 'integer'],
            [['is_prices_extended', 'sale_disabled'], 'integer'],
            [['text', 'sku', 'sale_disabled_text'], 'string'],
            [['url_alias'], 'string', 'max' => 100],
            [['image_id', 'schema_image_id'], 'integer'],

            [['label'], 'string', 'max' => 150],
            [['hidden', 'popular', 'is_new'], 'boolean'],

            [['type'], 'safe']
        ];
    }
}