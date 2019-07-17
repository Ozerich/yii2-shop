<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Color;

class ColorDTO extends Color implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'image' => $this->image ? $this->image->getUrl() : null
        ];
    }
}