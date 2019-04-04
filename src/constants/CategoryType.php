<?php

namespace ozerich\shop\constants;

use MyCLabs\Enum\Enum;

class CategoryType extends Enum
{
    const CATALOG = 'CATALOG';
    const CONDITIONAL = 'CONDITIONAL';

    public static function label($value)
    {
        switch ($value) {
            case self::CATALOG:
                return 'Каталог';
            case self::CONDITIONAL:
                return 'Условная категория';
            default:
                return 'Неизвестный';
        }
    }

    public static function getList()
    {
        return [
            self::CATALOG => self::label(self::CATALOG),
            self::CONDITIONAL => self::label(self::CONDITIONAL)
        ];
    }

}