<?php

namespace ozerich\shop\modules\admin\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\constants\CategoryConditionType;
use ozerich\shop\models\CategoryCondition;

class CategoryConditionalDTO extends CategoryCondition implements DTO
{
    public function toJSON()
    {
        return [
            'id' => $this->id,
            'filter' => $this->type == CategoryConditionType::PRICE ? 'PRICE' : $this->field_id,
            'compare' => $this->compare,
            'value' => $this->compare == 'ONE' ? explode(';', $this->value) : $this->value,
        ];
    }
}