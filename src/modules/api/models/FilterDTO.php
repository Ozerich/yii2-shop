<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;

class FilterDTO implements DTO
{
    private $id;

    private $type;

    private $name;

    private $values;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function setFilterType($value)
    {
        $this->type = $value;
    }

    public function addFilterValue($id, $name)
    {
        if ($id == null) {
            $this->values[] = ['id' => $name, 'label' => $name];
        } else {
            $this->values[] = ['id' => $id, 'label' => $name];
        }
    }

    public function toJSON()
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type
        ];

        if ($this->type == 'SELECT') {
            $result['values'] = $this->values;
        }

        return $result;
    }
}