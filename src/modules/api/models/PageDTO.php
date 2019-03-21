<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Page;

class PageDTO extends Page implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,

            'seo_title' => empty($this->meta_title) ? $this->meta_title : null,
            'seo_description' => $this->meta_description,
            'seo_image' => $this->metaImage ? $this->metaImage->getUrl() : null
        ];
    }
}