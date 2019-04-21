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
}