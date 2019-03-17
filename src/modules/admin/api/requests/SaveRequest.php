<?php

namespace ozerich\shop\modules\admin\api\requests;

use ozerich\api\request\RequestModel;

class SaveRequest extends RequestModel
{
    public $first_param_id;

    public $second_param_id;

    public $value;

    public function rules()
    {
        return [
            [['first_param_id', 'value'], 'required'],
            [['second_param_id'], 'integer']
        ];
    }
}