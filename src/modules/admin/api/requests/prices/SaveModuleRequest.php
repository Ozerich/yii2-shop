<?php

namespace ozerich\shop\modules\admin\api\requests\prices;

use ozerich\api\request\RequestModel;

class SaveModuleRequest extends RequestModel
{
    public $price;

    public $discount_mode;

    public $discount_value;

    public function rules()
    {
        return [
            [['price', 'discount_mode', 'discount_value'], 'safe']
        ];
    }
}