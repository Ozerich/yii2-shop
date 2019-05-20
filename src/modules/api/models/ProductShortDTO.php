<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\constants\FieldType;
use ozerich\shop\models\Product;
use ozerich\shop\traits\ServicesTrait;

class ProductShortDTO extends Product implements DTO
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
                $result[$productFieldValue->field_id] = $productFieldValue->field->type == FieldType::SELECT ? explode(';', $productFieldValue->value) : $productFieldValue->value;
            }
        }

        return $result;
    }

    public function toJSON()
    {
        return [
            'id' => $this->id,
            'url_alias' => $this->url_alias,
            'name' => $this->name,
            'label' => $this->label,

            'params' => $this->getParamsJSON(),

            'image' => $this->image ? [
                'small' => $this->image->getUrl('preview'),
                'big' => $this->image->getUrl('big-preview'),
                'original' => $this->image->getUrl()
            ] : null,

            'price' => (new PriceDTO($this))->toJSON(),
            'stock' => $this->stock,
            'stock_waiting_days' => $this->stock_waiting_days,

            'sale_disabled' => $this->sale_disabled ? true : false,
            'sale_disabled_text' => $this->sale_disabled ? $this->sale_disabled_text : null,

            'is_new' => $this->is_new ? true : false,
            'is_popular' => $this->popular ? true : false
        ];
    }
}