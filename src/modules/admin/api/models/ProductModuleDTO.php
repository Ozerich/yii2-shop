<?php

namespace ozerich\shop\modules\admin\api\models;


use ozerich\api\interfaces\DTO;
use ozerich\shop\models\ProductModule;

class ProductModuleDTO implements DTO
{
    private $model;

    public function __construct(ProductModule $module)
    {
        $this->model = $module;
    }

    public function toJSON()
    {
        return [
            'id' => $this->model->id,
            'name' => $this->model->name,
            'sku' => $this->model->sku
        ];
    }
}