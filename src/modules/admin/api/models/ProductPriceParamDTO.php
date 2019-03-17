<?php

namespace ozerich\shop\modules\admin\api\models;

use ozerich\shop\models\ProductPriceParam;
use ozerich\api\interfaces\DTO;

class ProductPriceParamDTO extends ProductPriceParam implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}