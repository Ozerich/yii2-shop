<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Banners;

class BannerDTO extends Banners implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'photo' => $this->photo ? $this->photo->getUrl() : null,
            'url' => $this->url,
            'area' => $this->area->alias
        ];
    }
}
