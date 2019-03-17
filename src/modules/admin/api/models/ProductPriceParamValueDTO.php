<?php

namespace ozerich\shop\modules\admin\api\models;

use ozerich\shop\models\ProductPriceParamValue;
use ozerich\api\interfaces\DTO;

class ProductPriceParamValueDTO extends ProductPriceParamValue implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}