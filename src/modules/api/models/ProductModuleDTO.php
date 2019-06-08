<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\ProductModule;
use ozerich\shop\traits\ServicesTrait;

class ProductModuleDTO extends ProductModule implements DTO
{
    use ServicesTrait;

    public function toJSON()
    {
        if ($this->product_value_id) {
            return [
                'id' => $this->id,
                'quantity' => $this->default_quantity,
                'product' => (new ProductShortDTO($this->productValue))->toJSON()
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'image' => $this->image ? $this->image->getUrl() : null,
            'params' => $this->params ? json_decode($this->params, true) : [],
            'quantity' => $this->default_quantity,
            'price' => $this->price,
            'price_with_discount' => $this->price_with_discount
        ];
    }
}