<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Category;
use ozerich\shop\models\CategoryField;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductFieldValue;
use ozerich\shop\models\ProductImage;
use ozerich\shop\traits\ServicesTrait;

class ProductFullDTO extends Product implements DTO
{
    use ServicesTrait;

    private function getParamsJSON()
    {
        $categoryFields = $this->category->categoryFields;

        $categoryFieldsIds = array_map(function (CategoryField $categoryField) {
            return $categoryField->field_id;
        }, $categoryFields);

        $params = array_filter($this->productFieldValues, function (ProductFieldValue $value) use ($categoryFieldsIds) {
            return in_array($value->field_id, $categoryFieldsIds);
        });

        $result = [];

        foreach ($params as $item) {
            $result[] = [
                'type' => 'FIELD',
                'model' => [
                    'label' => $item->field->name,
                    'image' => $item->field->image ? $item->field->image->getUrl() : null
                ],
                'value' => $this->productFieldsService()->getFieldPlainValue($item)
            ];
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

    private function getSeoDescription()
    {
        if (!empty($this->seo_description)) {
            return $this->seo_description;
        }

        return $this->name . ' – характеристики, фото, видео. Лучшая цена в Беларуси – ' . (($this->is_price_from || $this->is_prices_extended) ? 'от ' : '') . $this->price . ' руб. в интернет-магазине БелМебель.';
    }

    public function toJSON()
    {
        return [
            'id' => $this->id,
            'url_alias' => $this->url_alias,
            'name' => $this->name,
            'label' => $this->label,
            'image' => $this->image ? [
                'small' => $this->image->getUrl('preview'),
                'big' => $this->image->getUrl('big-preview'),
                'original' => $this->image->getUrl()
            ] : null,
            'schema' => $this->schemaImage ? [
                'preview' => $this->schemaImage->getUrl('schema-preview'),
                'original' => $this->schemaImage->getUrl()
            ] : null,
            'video' => $this->video,
            'text' => $this->text,
            'params' => $this->getParamsJSON(),

            'path' => $this->getPath(),

            'images' => array_map(function (ProductImage $image) {
                return [
                    'text' => $image->text,
                    'small' => $image->image->getUrl('gallery-preview'),
                    'big' => $image->image->getUrl('big-preview'),
                    'original' => $image->image->getUrl()
                ];
            }, $this->productImages),

            'h1_value' => empty($this->h1_value) ? $this->name : $this->h1_value,
            'seo_title' => empty($this->seo_title) ? 'Купить ' . $this->name . ' недорого – фото, цена, характеристики.' : $this->seo_title,
            'seo_description' => $this->getSeoDescription(),
            'seo_image' => $this->image ? $this->image->getUrl('og') : null,

            'price' => (new PriceDTO($this))->toJSON(),
            'stock' => $this->stock,
            'stock_waiting_days' => $this->stock_waiting_days,

            'sale_disabled' => $this->sale_disabled ? true : false,
            'sale_disabled_text' => $this->sale_disabled ? $this->sale_disabled_text : null,

            'is_new' => $this->is_new ? true : false,
            'is_popular' => $this->popular ? true : false,

            'manufacture' => $this->manufacture ? (new ManufactureDTO($this->manufacture))->toJSON() : null,
            'collection' => $this->collection ? (new CollectionDTO($this->collection))->toJSON() : null
        ];
    }
}