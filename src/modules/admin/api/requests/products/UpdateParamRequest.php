<?php

namespace ozerich\shop\modules\admin\api\requests\products;

use ozerich\api\request\RequestModel;

class UpdateParamRequest extends RequestModel
{
    public $product_id;
    public $field_id;
    public $value;

    public function rules()
    {
        return [
            [['product_id', 'field_id'], 'required'],
            [['value'], 'safe'],
        ];
    }
}