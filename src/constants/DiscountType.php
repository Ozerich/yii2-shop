<?php

namespace ozerich\shop\constants;

use MyCLabs\Enum\Enum;

class DiscountType extends Enum
{
    const FIXED = 'FIXED';
    const AMOUNT = 'AMOUNT';
    const PERCENT = 'PERCENT';
}