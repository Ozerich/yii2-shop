<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\constants\DiscountType;
use ozerich\shop\models\ProductPrice;
use ozerich\shop\traits\ServicesTrait;

class ProductPriceDTO implements DTO
{
    use ServicesTrait;

    /** @var ProductPrice */
    private $model;

    public function __construct(ProductPrice $productPrice)
    {
        $this->model = $productPrice;
    }

    private function getPriceWithDiscount()
    {
        switch ($this->model->discount_mode) {
            case DiscountType::FIXED:
                return $this->model->discount_value;
            case DiscountType::AMOUNT:
                return $this->model->value - $this->model->discount_value;
            case DiscountType::PERCENT:
                return $this->model->value - floor(($this->model->value / 100 * $this->model->discount_value));
            default:
                return $this->model->value;
        }
    }

    public function toJSON()
    {
        return [
            'price' => $this->productPricesService()->preparePriceForOutput($this->model->value, $this->model->product->currency_id),
            'price_with_discount' => $this->productPricesService()->preparePriceForOutput($this->getPriceWithDiscount(), $this->model->product->currency_id),
            'discount_mode' => $this->model->discount_mode,
            'discount_value' => $this->model->discount_value,
            'stock' => $this->model->stock,
            'stock_waiting_days' => $this->model->stock_waiting_days,
        ];
    }
}