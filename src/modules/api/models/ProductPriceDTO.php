<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\constants\DiscountType;
use ozerich\shop\models\ProductPrice;

class ProductPriceDTO implements DTO
{
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
            'price' => $this->model->value,
            'price_with_discount' => $this->getPriceWithDiscount(),
            'discount_mode' => $this->model->discount_mode,
            'discount_value' => $this->model->discount_value,
        ];
    }
}