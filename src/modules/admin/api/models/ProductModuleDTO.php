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
            'sku' => $this->model->sku,
            'image' => $this->model->image ? $this->model->image->getUrl() : null,
            'price' => $this->model->price,
            'price_with_discount' => $this->model->price_with_discount,
            'quantity' => $this->model->default_quantity
        ];
    }
}