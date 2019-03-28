<?php

namespace ozerich\shop\services\products;

use ozerich\shop\models\Product;
use ozerich\shop\models\ProductPriceParam;

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

        $paramsCount = ProductPriceParam::find()->andWhere('product_id=:product_id', [':product_id' => $product->id])->count();

        $prices = $product->prices;
        $min = null;
        foreach ($prices as $price) {
            if ($paramsCount == 2 && !$price->param_value_second_id || !$price->param_value_id) {
                continue;
            }
            if ($min === null || $price->value < $min) {
                $min = $price->value;
            }
        }

        $product->price = $min;
        $product->save(false, ['price']);
    }
}