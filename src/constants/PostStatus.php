<?php

namespace ozerich\shop\constants;

use MyCLabs\Enum\Enum;

class PostStatus extends Enum
{
    const PUBLISHED = 'PUBLISHED';
    const DRAFT = 'DRAFT';

    public static function label($value)
    {
        switch ($value) {
            case self::PUBLISHED:
                return 'Опубликовано';
            case self::DRAFT:
                return 'Черновик';
            default:
                return 'Неизвестный';
        }
    }

    public static function getList()
    {
        return [
            self::PUBLISHED => self::label(self::PUBLISHED),
            self::DRAFT => self::label(self::DRAFT),
        ];
    }

}