<?php

namespace ozerich\shop\traits;

use ozerich\shop\import\ImportProductService;
use ozerich\shop\import\ImportService;
use ozerich\shop\services\blog\BlogService;
use ozerich\shop\services\categories\CategoriesService;
use ozerich\shop\services\categories\CategoryFieldsService;
use ozerich\shop\services\categories\CategoryManufacturesService;
use ozerich\shop\services\categories\CategoryProductsService;
use ozerich\shop\services\menu\MenuService;
use ozerich\shop\services\products\ProductBaseService;
use ozerich\shop\services\products\ProductCollectionsService;
use ozerich\shop\services\products\ProductColorsService;
use ozerich\shop\services\products\ProductFieldsService;
use ozerich\shop\services\products\ProductGetService;
use ozerich\shop\services\products\ProductMediaService;
use ozerich\shop\services\products\ProductModulesService;
use ozerich\shop\services\products\ProductPricesService;
use ozerich\shop\services\products\ProductSeoService;
use ozerich\shop\services\search\SearchService;
use ozerich\shop\services\settings\SettingsService;

trait ServicesTrait
{
    private $productFieldsService = null;

    private $productMediaService = null;

    private $productGetService = null;

    private $productPricesService = null;

    private $productColorsService = null;

    private $productBaseService = null;

    private $categoriesService = null;

    private $categoryFieldsService = null;

    private $categoryProductsService = null;

    private $categoryManufacturesService = null;

    private $searchService = null;

    private $menuService = null;

    private $settingsService = null;

    private $blogService = null;

    private $productCollectionsService = null;

    private $productModulesService = null;

    private $productSeoService = null;

    private $importProductService = null;

    /**
     * @return ImportProductService
     */
    public function importProductService()
    {
        if ($this->importProductService === null) {
            $this->importProductService = new ImportProductService();
        }

        return $this->importProductService;
    }

    /**
     * @return ProductSeoService
     */
    public function productSeoService()
    {
        if ($this->productSeoService === null) {
            $this->productSeoService = new ProductSeoService();
        }

        return $this->productSeoService;
    }

    /**
     * @return ProductModulesService
     */
    public function productModulesService()
    {
        if ($this->productModulesService === null) {
            $this->productModulesService = new ProductModulesService();
        }

        return $this->productModulesService;
    }

    /**
     * @return ProductCollectionsService
     */
    public function productCollectionsService()
    {
        if ($this->productCollectionsService === null) {
            $this->productCollectionsService = new ProductCollectionsService();
        }

        return $this->productCollectionsService;
    }

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
     * @return ProductColorsService
     */
    public function productColorsService()
    {
        if ($this->productColorsService === null) {
            $this->productColorsService = new ProductColorsService();
        }

        return $this->productColorsService;
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
     * @return ProductBaseService
     */
    public function productBaseService()
    {
        if ($this->productBaseService === null) {
            $this->productBaseService = new ProductBaseService();
        }

        return $this->productBaseService;
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

    /**
     * @return MenuService
     */
    public function menuService()
    {
        if ($this->menuService === null) {
            $this->menuService = new MenuService();
        }

        return $this->menuService;
    }

    /**
     * @return SettingsService
     */
    public function settingsService()
    {
        if ($this->settingsService === null) {
            $this->settingsService = new SettingsService();
        }

        return $this->settingsService;
    }

    /**
     * @return BlogService
     */
    public function blogService()
    {
        if ($this->blogService === null) {
            $this->blogService = new BlogService();
        }

        return $this->blogService;
    }
}