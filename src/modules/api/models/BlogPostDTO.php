<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\BlogCategory;
use ozerich\shop\models\BlogPost;

class BlogPostDTO extends BlogPost implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->title,
            'excerpt' => $this->excerpt,
            'url_alias' => $this->url_alias,
            'image' => $this->image ? $this->image->getUrl('preview') : null,
            'url' => $this->getUrl()
        ];
    }
}