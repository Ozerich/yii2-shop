<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\ProductCollection;

class CollectionFullDTO extends ProductCollection implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->getUrl(true),
            'image' => $this->image ? $this->image->getUrl() : null,
            'content' => $this->content,
            'manufacture' => $this->manufacture ? (new ManufactureDTO($this->manufacture))->toJSON() : null
        ];
    }
}