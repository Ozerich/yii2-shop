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

    private function toJSONFromCatalog()
    {
        return [
            'id' => $this->model->id,
            'name' => $this->model->productValue->name,
            'sku' => $this->model->productValue->sku,
            'image' => $this->model->productValue->image ? $this->model->productValue->image->getUrl() : null,
            'price' => $this->model->productValue->price,
            'price_with_discount' => $this->model->productValue->price_with_discount,
            'quantity' => $this->model->default_quantity,
            'params' => []
        ];
    }

    public function toJSON()
    {
        if ($this->model->product_value_id) {
            return $this->toJSONFromCatalog();
        }

        return [
            'id' => $this->model->id,
            'name' => $this->model->name,
            'sku' => $this->model->sku,
            'image' => $this->model->image ? $this->model->image->getUrl() : null,
            'price' => $this->model->price,
            'price_with_discount' => $this->model->price_with_discount,
            'quantity' => $this->model->default_quantity,
            'params' => $this->model->params ? json_decode($this->model->params, true) : []
        ];
    }
}