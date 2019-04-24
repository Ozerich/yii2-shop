<?php

namespace ozerich\shop\modules\admin\api\requests\prices;

use ozerich\api\request\RequestModel;

class SaveRequest extends RequestModel
{
    public $first_param_id;

    public $second_param_id;

    public $value;

    public $discount_mode;

    public $discount_value;

    public $stock;

    public $stock_waiting_days;

    public $price;

    public function rules()
    {
        return [
            [['first_param_id', 'second_param_id', 'price'], 'integer'],

            [['value'], 'integer'],
            [['discount_mode'], 'string'],
            [['discount_value'], 'integer'],

            [['stock'], 'string'],
            [['stock_waiting_days'], 'integer']
        ];
    }
}