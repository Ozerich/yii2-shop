<?php

namespace ozerich\shop\constants;

use MyCLabs\Enum\Enum;

class FieldType extends Enum
{
    const INTEGER = 'INTEGER';
    const BOOLEAN = 'BOOLEAN';
    const STRING = 'STRING';
    const SELECT = 'SELECT';

    public static function label($value)
    {
        switch ($value) {
            case self::INTEGER:
                return 'Число';
            case self::BOOLEAN:
                return 'Да / Нет';
            case self::STRING:
                return 'Строка';
            case self::SELECT:
                return 'Выбор';
            default:
                return 'Неизвестный';
        }
    }

    public static function getList()
    {
        return [
            self::STRING => self::label(self::STRING),
            self::INTEGER => self::label(self::INTEGER),
            self::BOOLEAN => self::label(self::BOOLEAN),
            self::SELECT => self::label(self::SELECT),
        ];
    }

}