<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Product;

class PriceDTO implements DTO
{
    private $model;

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function toJSON()
    {
        return [
            'is_extended' => $this->model->is_prices_extended ? true : false,

            'is_hidden' => $this->model->price_hidden ? true : false,
            'hidden_text' => $this->model->price_hidden_text,

            'price' => $this->model->price,
            'price_with_discount' => $this->model->price_with_discount,

            'discount_mode' => $this->model->discount_mode,
            'discount_value' => $this->model->discount_value,

            'is_from' => $this->model->is_price_from,
            'note' => $this->model->price_note
        ];
    }
}