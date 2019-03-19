<?php

namespace ozerich\shop\traits;

use ozerich\shop\services\categories\CategoriesService;
use ozerich\shop\services\products\ProductFieldsService;
use ozerich\shop\services\products\ProductMediaService;

trait ServicesTrait
{
    private $productFieldsService = null;

    private $productMediaService = null;

    private $categoriesService = null;

    /**
     * @return ProductFieldsService
     */
    public function productFieldsService()
    {
        if ($this->productFieldsService === null) {
            $this->productFieldsService = new ProductFieldsService();
        }

        return $this->productFieldsService;
    }

    /**
     * @return CategoriesService
     */
    public function categoriesService()
    {
        if ($this->categoriesService === null) {
            $this->categoriesService = new CategoriesService();
        }

        return $this->categoriesService;
    }

    /**
     * @return ProductMediaService
     */
    public function productMediaService()
    {
        if ($this->productMediaService === null) {
            $this->productMediaService = new ProductMediaService();
        }

        return $this->productMediaService;
    }
}