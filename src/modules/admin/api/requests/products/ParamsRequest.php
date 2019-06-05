<?php

namespace ozerich\shop\modules\admin\api\requests\products;

use ozerich\api\request\RequestModel;

class ParamsRequest extends RequestModel
{
    public $category_id;

    public $fields;

    public function rules()
    {
        return [
            [['category_id', 'fields'], 'required'],
            [['category_id'], 'integer'],
        ];
    }
}