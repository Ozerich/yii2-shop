<?php

namespace ozerich\shop\constants;

use MyCLabs\Enum\Enum;

class CategoryConditionType extends Enum
{
    const FIELD = 'FIELD';
    const PRICE = 'PRICE';
    const CATEGORY = 'CATEGORY';
    const MANUFACTURE = 'MANUFACTURE';
    const COLOR = 'COLOR';
    const DISCOUNT = 'DISCOUNT';
}