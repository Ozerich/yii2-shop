<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Product;
use ozerich\shop\traits\ServicesTrait;

class PriceDTO implements DTO
{
    use ServicesTrait;

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

            'price' => $this->productPricesService()->preparePriceForOutput($this->model->price, $this->model->currency_id),
            'price_with_discount' => $this->productPricesService()->preparePriceForOutput($this->model->price_with_discount ? $this->model->price_with_discount : $this->model->price, $this->model->currency_id),

            'original_price' => $this->model->price,
            'original_currency' => $this->model->currency_id,

            'discount_mode' => $this->model->discount_mode,
            'discount_value' => $this->model->discount_value,

            'is_from' => $this->model->is_price_from,
            'note' => $this->model->price_note,

            'stock' => $this->model->stock,
            'stock_waiting_days' => $this->model->stock_waiting_days,
        ];
    }
}