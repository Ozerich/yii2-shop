<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\BlogCategory;
use ozerich\shop\models\BlogPost;

class BlogPostFullDTO extends BlogPost implements DTO
{
    private function getPath()
    {
        $parents = [];
        $parent = $this->category;

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
            'url_alias' => $this->url_alias,
            'name' => $this->title,
            'content' => $this->content,
            'path' => $this->getPath(),

            'page_title' => $this->page_title,
            'meta_description' => $this->meta_description
        ];
    }
}