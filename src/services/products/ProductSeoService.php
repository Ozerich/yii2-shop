<?php

namespace ozerich\shop\services\products;

use ozerich\shop\constants\SettingOption;
use ozerich\shop\models\Product;
use ozerich\shop\traits\ServicesTrait;

class ProductSeoService
{
    use ServicesTrait;

    private function getTemplateParams(Product $product)
    {
        $plainPrice = (($product->is_price_from || $product->is_prices_extended) ? 'от ' : '') . $product->price . ' руб.';

        return [
            'name' => $product->name,
            'plain_price' => $plainPrice,
        ];
    }

    /**
     * @param Product $product
     * @param string $template
     * @return string
     */
    private function fromTemplate(Product $product, $template)
    {
        foreach ($this->getTemplateParams($product) as $param => $value) {
            $template = str_replace('{{' . $param . '}}', $value, $template);
        }

        return $template;
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getPageTitle(Product $product)
    {
        if (!empty($product->seo_title)) {
            return $product->seo_title;
        }

        $category = $product->category;
        if ($category && !empty($category->seo_title_products_template)) {
            return $this->fromTemplate($product, $category->seo_title_products_template);
        }

        $global = $this->settingsService()->get(SettingOption::SEO_TITLE_TEMPLATE);
        if (!empty($global)) {
            return $this->fromTemplate($product, $global);
        }

        return $product->name;
    }

    /**
     * @param Product $product
     * @return string|null
     */
    public function getMetaDescription(Product $product)
    {
        if (!empty($product->seo_description)) {
            return $product->seo_description;
        }

        $category = $product->category;
        if ($category && !empty($category->seo_description_products_template)) {
            return $this->fromTemplate($product, $category->seo_description_products_template);
        }

        $global = $this->settingsService()->get(SettingOption::SEO_DESCRIPTION_TEMPLATE);
        if (!empty($global)) {
            return $this->fromTemplate($product, $global);
        }

        return null;
    }

    /**
     * @param Product $product
     * @return string|null
     */
    public function getOgImageUrl(Product $product)
    {
        return $product->image ? $product->image->getUrl('og') : null;
    }
}