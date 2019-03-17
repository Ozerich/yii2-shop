<?php

namespace ozerich\shop\modules\api\models;

use ozerich\shop\models\Product;
use ozerich\api\interfaces\DTO;

class ProductDTO extends Product implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'image' => $this->image ? $this->image->getUrl() : null,
            'url_alias' => $this->url_alias
        ];
    }
}