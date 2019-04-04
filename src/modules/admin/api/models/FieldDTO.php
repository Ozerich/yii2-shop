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
            'group_id' => $this->group_id,
            'group_name' => $this->group ? $this->group->name : null,
            'value_suffix' => $this->value_suffix,
            'value_prefix' => $this->value_prefix,
            'values' => $this->values
        ];
    }
}