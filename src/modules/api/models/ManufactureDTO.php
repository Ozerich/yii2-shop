<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Manufacture;

class ManufactureDTO extends Manufacture implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image ? $this->image->getUrl() : null
        ];
    }
}