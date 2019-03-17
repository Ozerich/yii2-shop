<?php

namespace ozerich\shop\structures;

use ozerich\shop\models\Field;

class ProductField
{
    /** @var Field */
    private $field;

    private $value;

    public function setField(Field $field)
    {
        $this->field = $field;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getValue()
    {
        return $this->value;
    }
}