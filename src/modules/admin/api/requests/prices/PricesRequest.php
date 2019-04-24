<?php

namespace ozerich\shop\modules\admin\api\requests\prices;

use ozerich\api\request\RequestModel;

class PricesRequest extends RequestModel
{
    public $category_id;

    public $manufacture_id;

    public $without_price;

    public function rules()
    {
        return [
            [['category_id', 'manufacture_id', 'without_price'], 'safe']
        ];
    }
}