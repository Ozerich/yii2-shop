<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\BannerAreas;

class BannerAreaDTO extends BannerAreas implements DTO
{
    public function toJSON()
    {
        return [
            'alias' => $this->alias,
            'name' => $this->name
        ];
    }
}
