<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Product;
use ozerich\shop\traits\ServicesTrait;

class ProductDTO extends Product implements DTO
{
    use ServicesTrait;

    private function getParamsJSON()
    {
        $categoryFields = $this->category->categoryFields;

        $field_ids = [];
        foreach ($categoryFields as $categoryField) {
            $field_ids[] = $categoryField->field_id;
        }

        $result = [];

        foreach ($this->productFieldValues as $productFieldValue) {
            if (in_array($productFieldValue->field_id, $field_ids)) {
                $result[$productFieldValue->field_id] = $productFieldValue->value;
            }
        }

        return $result;
    }

    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'popular_weight' => $this->popular_weight,
            'image' => $this->image ? $this->image->getUrl('preview') : null,
            'url_alias' => $this->url_alias,
            'sku' => $this->sku,

            'price' => $this->price,
            'is_prices_extended' => $this->is_prices_extended ? true : false,
            'price_hidden' => $this->price_hidden,
            'price_hidden_text' => $this->price_hidden_text,

            'sale_disabled' => $this->sale_disabled ? true : false,
            'sale_disabled_text' => $this->sale_disabled ? $this->sale_disabled_text : null,

            'params' => $this->getParamsJSON(),
        ];
    }
}