<?php

namespace ozerich\shop\services\products;

use ozerich\shop\models\Product;

class ProductPricesService
{
    /**
     * @param Product $product
     */
    public function updateProductPrice(Product $product)
    {
        if (!$product->is_prices_extended) {
            return;
        }

        $prices = $product->prices;
        $min = null;
        foreach ($prices as $price) {
            if ($min === null || $price->value < $min) {
                $min = $price->value;
            }
        }

        $product->price = $min;
        $product->save(false, ['price']);
    }
}