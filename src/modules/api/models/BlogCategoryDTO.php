<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\BlogCategory;

class BlogCategoryDTO extends BlogCategory implements DTO
{
    private function getParents()
    {
        $parents = [];
        $parent = $this->parent;

        while ($parent) {
            $parents[] = $parent;
            $parent = $parent->parent;
        }

        return array_reverse(array_map(function (BlogCategory $parent) {
            return [
                'id' => $parent->id,
                'url' => $parent->getUrl(),
                'name' => $parent->name
            ];
        }, $parents));
    }

    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'url' => $this->getUrl(true),
            'image' => $this->image ? $this->image->getUrl('preview') : null,
            'parents' => $this->getParents()
        ];
    }
}