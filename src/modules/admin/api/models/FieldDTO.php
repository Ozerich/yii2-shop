<?php

namespace ozerich\shop\modules\admin\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Field;

class FieldDTO extends Field implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'value_suffix' => $this->value_suffix,
            'value_prefix' => $this->value_prefix,
            'yes_label' => $this->yes_label,
            'no_label' => $this->no_label,
            'multiple' => $this->multiple ? true : false,
            'values' => $this->values,
            'filter_enabled' => $this->filter_enabled ? true : false
        ];
    }
}