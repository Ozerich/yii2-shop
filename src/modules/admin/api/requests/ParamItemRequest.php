<?php

namespace ozerich\shop\modules\admin\api\requests;

use ozerich\api\request\RequestModel;

class ParamItemRequest extends RequestModel
{
    public $param_id;

    public $name;

    public $description;

    public function rules()
    {
        return [
            [['name', 'param_id'], 'required'],
            [['description'], 'safe']
        ];
    }
}