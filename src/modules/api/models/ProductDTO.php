<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Product;

class ProductDTO extends Product implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'popular_weight' => $this->popular_weight,
            'is_prices_extended' => $this->is_prices_extended ? true : false,
            'image' => $this->image ? $this->image->getUrl('preview') : null,
            'url_alias' => $this->url_alias
        ];
    }
}