<?php

namespace ozerich\shop\plugins;

use ozerich\shop\models\Product;

class ProductTabsStorage
{
    /** @var IProductTab[] */
    public static $productTabs = [];

    static function register(IProductTab $productTab)
    {
        self::$productTabs[] = $productTab;
    }

    static function getProductTabs(Product $product)
    {
        $result = [];

        foreach (self::$productTabs as $tab) {
            if ($tab->isTabVisible($product) == false) {
                continue;
            }

            $result[] = [
                'label' => $tab->tabLabel(),
                'content' => $tab->render($product)
            ];
        }

        return $result;
    }
}