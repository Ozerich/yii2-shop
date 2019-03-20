<?php

namespace ozerich\shop\modules\admin\api\requests\fields;

use ozerich\api\request\RequestModel;

class GroupRequest extends RequestModel
{
    public $name;

    public function rules()
    {
        return [
            [['name'], 'required']
        ];
    }
}