<?php

namespace ozerich\shop\constants;

use MyCLabs\Enum\Enum;

class CategoryConditionCompare extends Enum
{
    const EQUAL = 'EQUAL';
    const LESS = 'LESS';
    const MORE = 'MORE';
    const IN = 'IN';
}