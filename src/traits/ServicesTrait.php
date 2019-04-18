<?php

namespace ozerich\shop\traits;

use ozerich\shop\services\categories\CategoriesService;
use ozerich\shop\services\categories\CategoryFieldsService;
use ozerich\shop\services\categories\CategoryManufacturesService;
use ozerich\shop\services\categories\CategoryProductsService;
use ozerich\shop\services\products\ProductFieldsService;
use ozerich\shop\services\products\ProductGetService;
use ozerich\shop\services\products\ProductMediaService;
use ozerich\shop\services\products\ProductPricesService;
use ozerich\shop\services\search\SearchService;

trait ServicesTrait
{
    private $productFieldsService = null;

    private $productMediaService = null;

    private $productGetService = null;

    private $productPricesService = null;

    private $categoriesService = null;

    private $categoryFieldsService = null;

    private $categoryProductsService = null;

    private $categoryManufacturesService = null;

    private $searchService = null;

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

    /**
     * @return CategoryProductsService
     */
    public function categoryProductsService()
    {
        if ($this->categoryProductsService === null) {
            $this->categoryProductsService = new CategoryProductsService();
        }

        return $this->categoryProductsService;
    }

    /**
     * @return CategoryManufacturesService
     */
    public function categoryManufacturesService()
    {
        if ($this->categoryManufacturesService === null) {
            $this->categoryManufacturesService = new CategoryManufacturesService();
        }

        return $this->categoryManufacturesService;
    }

    /**
     * @return SearchService
     */
    public function searchService()
    {
        if ($this->searchService === null) {
            $this->searchService = new SearchService();
        }

        return $this->searchService;
    }
}