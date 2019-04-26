<?php

namespace ozerich\shop\modules\admin\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Product;

class ProductPriceCommonDTO extends Product implements DTO
{
    public function toJSON()
    {
        return [
            'is_extended_mode' => $this->is_prices_extended ? true : false,
            'price' => $this->price,
            'price_hidden' => $this->price_hidden,
            'price_hidden_text' => $this->price_hidden_text,
            'discount_mode' => $this->discount_mode,
            'discount_value' => $this->discount_value,
            'stock' => $this->stock,
            'stock_waiting_days' => $this->stock_waiting_days,
            'price_note' => $this->price_note,
            'is_price_from' => $this->is_price_from ? true : false,
            'currency_id' => $this->currency_id
        ];
    }
}