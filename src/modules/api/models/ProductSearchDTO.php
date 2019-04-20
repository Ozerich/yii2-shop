<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Category;
use ozerich\shop\models\Product;

class ProductSearchDTO extends Product implements DTO
{
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
            'image' => $this->image ? $this->image->getUrl('preview') : null,
            'url_alias' => $this->url_alias,

            'price' => (new PriceDTO($this))->toJSON(),

            'sale_disabled' => $this->sale_disabled ? true : false,
            'sale_disabled_text' => $this->sale_disabled ? $this->sale_disabled_text : null,

            'path' => $this->getPath(),
        ];
    }
}