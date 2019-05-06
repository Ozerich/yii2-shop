<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\BlogPost;

class BlogPostDTO extends BlogPost implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->title,
            'excerpt' => $this->excerpt,
            'image' => $this->image ? [
                'middle' => $this->image->getUrl('preview'),
                'small' => $this->image->getUrl('small-preview'),
            ] : null,
            'url' => $this->getUrl()
        ];
    }
}