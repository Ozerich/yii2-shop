<?php

namespace ozerich\shop\modules\admin\api\models;

use ozerich\shop\models\ProductPrice;
use ozerich\api\interfaces\DTO;

class ProductPriceDTO extends ProductPrice implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'value_id' => $this->param_value_id,
            'second_value_id' => $this->param_value_second_id,
            'value' => $this->value
        ];
    }
}