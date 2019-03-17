<?php

namespace ozerich\shop\modules\admin\api\requests;

use ozerich\api\request\RequestModel;

class ParamRequest extends RequestModel
{
    public $name;

    public $product_id;

    public function rules()
    {
        return [
            [['name', 'product_id'], 'required']
        ];
    }
}