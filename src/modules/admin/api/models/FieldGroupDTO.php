<?php

namespace ozerich\shop\modules\admin\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\FieldGroup;

class FieldGroupDTO extends FieldGroup implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}