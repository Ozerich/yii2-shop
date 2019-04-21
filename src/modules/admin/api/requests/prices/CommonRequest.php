<?php

namespace ozerich\shop\modules\admin\api\requests\prices;

use ozerich\api\request\RequestModel;
use ozerich\shop\constants\DiscountType;

class CommonRequest extends RequestModel
{
    public $price;

    public $disabled;

    public $disabled_text;

    public $discount_mode;

    public $discount_value;

    public $stock;

    public $stock_waiting_days;


    public function rules()
    {
        return [
            [['price', 'disabled'], 'required'],
            [['price'], 'number'],
            [['disabled_text'], 'string'],
            [['discount_mode'], 'string'],
            [['discount_value'], 'validateDiscountValue'],
            [['stock'], 'string'],
            [['stock_waiting_days'], 'integer']
        ];
    }

    public function validateDiscountValue()
    {
        $value = (int)$this->discount_value;

        if ($this->discount_mode == DiscountType::PERCENT) {
            if ($value < 0 || $value > 100) {
                $this->addError('discount_value', 'Скидка должна быть между 0 и 100');
                return;
            }
        }

        if ($this->discount_mode == DiscountType::AMOUNT) {
            if ($value > $this->price) {
                $this->addError('discount_value', 'Размер скидки не может быть больше цены');
                return;
            }
        }

        if ($this->discount_mode == DiscountType::FIXED) {
            if ($value > $this->price) {
                $this->addError('discount_value', 'Цена со скидкой должна быть меньше');
                return;
            }
        }
    }
}