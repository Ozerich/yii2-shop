<?php

namespace ozerich\shop\traits;

use ozerich\shop\services\categories\CategoriesService;
use ozerich\shop\services\categories\CategoryFieldsService;
use ozerich\shop\services\products\ProductFieldsService;
use ozerich\shop\services\products\ProductGetService;
use ozerich\shop\services\products\ProductMediaService;
use ozerich\shop\services\products\ProductPricesService;

trait ServicesTrait
{
    private $productFieldsService = null;

    private $productMediaService = null;

    private $productGetService = null;

    private $productPricesService = null;

    private $categoriesService = null;

    private $categoryFieldsService = null;

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
     * @return ProductPricesService
     */
    public function productPricesService()
    {
        if ($this->productPricesService === null) {
            $this->productPricesService = new ProductPricesService();
        }

        return $this->productPricesService;
    }

    /**
     * @return ProductGetService
     */
    public function productGetService()
    {
        if ($this->productGetService === null) {
            $this->productGetService = new ProductGetService();
        }

        return $this->productGetService;
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
     * @return CategoryFieldsService
     */
    public function categoryFieldsService()
    {
        if ($this->categoryFieldsService === null) {
            $this->categoryFieldsService = new CategoryFieldsService();
        }

        return $this->categoryFieldsService;
    }
}