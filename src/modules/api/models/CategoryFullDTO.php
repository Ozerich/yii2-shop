<?php

namespace ozerich\shop\modules\api\models;

use ozerich\shop\models\Category;
use ozerich\api\interfaces\DTO;

class CategoryFullDTO extends Category implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url_alias' => $this->url_alias,
            'image' => $this->image ? $this->image->getUrl() : null,
            'text' => $this->text,
            'children' => array_map(function (Category $category) {
                return (new CategoryDTO($category))->toJSON();
            }, $this->categories)
        ];
    }
}