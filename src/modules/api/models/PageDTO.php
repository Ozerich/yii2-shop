<?php

namespace ozerich\shop\modules\api\models;

use ozerich\shop\models\Page;
use ozerich\api\interfaces\DTO;

class PageDTO extends Page implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url
        ];
    }
}