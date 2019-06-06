<?php

namespace ozerich\shop\constants;

use MyCLabs\Enum\Enum;

class ProductType extends Enum
{
    const SIMPLE = 'SIMPLE';
    const MODULAR = 'MODULAR';

    public static function label($value)
    {
        switch ($value) {
            case self::SIMPLE:
                return 'Простой товар';
            case self::MODULAR:
                return 'Модульный товар';
            default:
                return 'Неизвестный';
        }
    }

    public static function list()
    {
        return [
            self::SIMPLE => self::label(self::SIMPLE),
            self::MODULAR => self::label(self::MODULAR),
        ];
    }
}