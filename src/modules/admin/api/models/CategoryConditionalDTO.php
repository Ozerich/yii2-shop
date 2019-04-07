<?php

namespace ozerich\shop\modules\admin\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\constants\CategoryConditionType;
use ozerich\shop\models\CategoryCondition;

class CategoryConditionalDTO extends CategoryCondition implements DTO
{
    public function toJSON()
    {
        $value = $this->value;
        if ($this->compare == 'ONE') {
            $value = explode(';', $this->value);
            if($this->type == CategoryConditionType::CATEGORY){
                $value = array_map('intval', $value);
            }
        }

        return [
            'id' => $this->id,
            'filter' => $this->type == CategoryConditionType::PRICE ? 'PRICE' : ($this->type == CategoryConditionType::CATEGORY ? 'CATEGORY' : $this->field_id),
            'compare' => $this->compare,
            'value' => $value
        ];
    }
}