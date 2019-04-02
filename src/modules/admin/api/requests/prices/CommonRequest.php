<?php

namespace ozerich\shop\modules\admin\api\requests\prices;

use ozerich\api\request\RequestModel;

class CommonRequest extends RequestModel
{
    public $price;

    public $disabled;

    public $disabled_text;

    public function rules()
    {
        return [
            [['price', 'disabled'], 'required'],
            [['price'], 'number'],
            [['disabled_text'], 'string']
        ];
    }
}