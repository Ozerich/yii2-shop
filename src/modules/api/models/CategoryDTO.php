<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Category;

class CategoryDTO extends Category implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url_alias' => $this->url_alias,
            'image' => $this->image ? [
                'middle' => $this->image->getUrl('preview'),
                'small' => $this->image->getUrl('small-preview')
            ] : null,
            'url' => $this->getUrl()
        ];
    }
}