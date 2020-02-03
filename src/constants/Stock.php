<?php

namespace ozerich\shop\constants;

use MyCLabs\Enum\Enum;

class Stock extends Enum
{
    const SHOP = 'IN_SHOP';
    const STOCK = 'STOCK';
    const WAITING = 'WAITING';
    const NO = 'NO';

    public static function toInteger($value)
    {
        switch ($value) {
            case self::SHOP:
                return 3;
            case self::WAITING:
                return 1;
            case self::STOCK:
                return 2;
            default:
                return 0;
        }
    }
    public static function toLabel($value)
    {
        switch ($value) {
            case self::SHOP:
                return 'В магазине';
            case self::WAITING:
                return 'Под заказ';
            case self::STOCK:
                return 'На складе';
            default:
                return 'Нет в наличии';
        }
    }

    public static function toValue($label)
    {
        switch ($label) {
            case 'В магазине':
                return self::SHOP;
            case 'Под заказ':
                return self::WAITING;
            case 'На складе':
                return self::STOCK;
            default:
                return self::NO;
        }
    }
}
