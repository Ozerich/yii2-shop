<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\constants\FieldType;
use ozerich\shop\models\Category;
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
                $result[$productFieldValue->field_id] = $productFieldValue->field->type == FieldType::SELECT ? explode(';', $productFieldValue->value) : $productFieldValue->value;
            }
        }

        return $result;
    }

    public function getPath()
    {
        $categories = [$this->category];

        $max_level = null;
        $max = null;

        foreach ($categories as $category) {
            if ($max_level === null || $category->level > $max_level) {
                $max_level = $category->level;
                $max = $category;
            }
        }

        $parents = [$max];
        $parent = $max->parent;

        while ($parent) {
            $parents[] = $parent;
            $parent = $parent->parent;
        }

        return array_reverse(array_map(function (Category $category) {
            return [
                'id' => $category->id,
                'url' => $category->getUrl(),
                'name' => $category->name
            ];
        }, $parents));
    }

    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'popular_weight' => $this->popular_weight,
            'image' => $this->image ? $this->image->getUrl('preview') : null,
            'url_alias' => $this->url_alias,
            'sku' => $this->sku,

            'is_new' => $this->is_new ? true : false,
            'is_popular' => $this->popular ? true : false,

            'price' => (new PriceDTO($this))->toJSON(),
            'stock' => $this->stock,
            'stock_waiting_days' => $this->stock_waiting_days,

            'sale_disabled' => $this->sale_disabled ? true : false,
            'sale_disabled_text' => $this->sale_disabled ? $this->sale_disabled_text : null,

            'manufacture_id' => $this->manufacture_id,

            'params' => $this->getParamsJSON(),
            'path' => $this->getPath(),
        ];
    }
}