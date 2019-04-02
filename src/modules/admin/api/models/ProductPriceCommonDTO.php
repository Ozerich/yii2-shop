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
        ];
    }
}