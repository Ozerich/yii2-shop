<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\ProductCollection;

class CollectionDTO extends ProductCollection implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->getUrl(),
            'image' => $this->image ? $this->image->getUrl('preview') : null
        ];
    }
}