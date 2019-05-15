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

        $filter = $this->field_id;
        if(in_array($this->type, [CategoryConditionType::PRICE, CategoryConditionType::CATEGORY, CategoryConditionType::MANUFACTURE])){
            $filter = $this->type;
        }

        return [
            'id' => $this->id,
            'filter' => $filter,
            'compare' => $this->compare,
            'value' => $filter == CategoryConditionType::MANUFACTURE ? (int)$value : $value
        ];
    }
}