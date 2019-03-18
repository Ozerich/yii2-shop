<?php

namespace app\modules\api\requests\order;

use ozerich\api\request\RequestModel;

class SubmitRequest extends RequestModel
{
    public $name;

    public $phone;

    public function rules()
    {
        return [
            [['name', 'phone'], 'required']
        ];
    }
}