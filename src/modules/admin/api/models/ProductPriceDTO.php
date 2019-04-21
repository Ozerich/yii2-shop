<?php

namespace ozerich\shop\modules\admin\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\ProductPrice;

class ProductPriceDTO extends ProductPrice implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'value_id' => $this->param_value_id,
            'second_value_id' => $this->param_value_second_id,
            'value' => $this->value,
            'discount_mode' => $this->discount_mode,
            'discount_value' => $this->discount_value,
            'stock' => $this->stock,
            'stock_waiting_days' => $this->stock_waiting_days
        ];
    }
}