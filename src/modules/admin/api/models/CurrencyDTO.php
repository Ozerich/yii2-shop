<?php

namespace ozerich\shop\modules\admin\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Currency;
use ozerich\shop\models\Field;

class CurrencyDTO extends Currency implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'label' => $this->name,
        ];
    }
}