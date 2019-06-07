<?php

namespace ozerich\shop\modules\admin\api\requests\modules;

use ozerich\api\request\RequestModel;

class ModelRequest extends RequestModel
{
    public $name;

    public $sku;

    public $comment;

    public $price;

    public $discount_mode;

    public $discount_value;

    public $images;

    public function rules()
    {
        return [
            [['name', 'price'], 'required'],
            [['sku', 'comment', 'discount_mode', 'discount_value'], 'safe'],
            [['images'], 'safe']
        ];
    }
}