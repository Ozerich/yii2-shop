<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;

class UpdateProductForm extends Product
{
    public $name;

    public $image_id;

    public $text;

    public $url_alias;

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
            [['sale_disabled'], 'integer'],
            [['text', 'sku', 'sale_disabled_text'], 'string'],
            [['url_alias'], 'string', 'max' => 100],
            [['image_id'], 'integer'],

            [['label'], 'string', 'max' => 150],
            [['hidden', 'popular', 'is_new'], 'boolean'],

            [['type'], 'safe']
        ];
    }
}
