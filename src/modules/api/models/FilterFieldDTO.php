<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Field;

class FilterFieldDTO extends Field implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'values' => $this->values
        ];
    }
}