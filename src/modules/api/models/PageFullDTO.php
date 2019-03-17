<?php

namespace ozerich\shop\modules\api\models;

use ozerich\shop\models\Page;
use ozerich\api\interfaces\DTO;

class PageFullDTO extends Page implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_image' => $this->metaImage ? $this->metaImage->getUrl() : null,
        ];
    }
}