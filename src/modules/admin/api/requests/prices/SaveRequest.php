<?php

namespace ozerich\shop\modules\admin\api\requests\prices;

use ozerich\api\request\RequestModel;
use ozerich\shop\constants\DiscountType;

class SaveRequest extends RequestModel
{
    public $first_param_id;

    public $second_param_id;

    public $value = null;

    public $discount_mode = null;

    public $discount_value = null;

    public function rules()
    {
        return [
            [['first_param_id'], 'required'],
            [['second_param_id'], 'integer'],

            [['value'], 'integer'],
            [['discount_mode'], 'string'],
            [['discount_value'], 'integer']
        ];
    }
}