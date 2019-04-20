<?php

namespace ozerich\shop\services\products;

use ozerich\shop\constants\DiscountType;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductPriceParam;
use ozerich\shop\traits\ServicesTrait;

class ProductPricesService
{
    use ServicesTrait;

    private function getSimplePriceWithDiscount(Product $product)
    {
        switch ($product->discount_mode) {
            case DiscountType::FIXED:
                return $product->discount_value;
            case DiscountType::AMOUNT:
                return $product->price - $product->discount_value;
            case DiscountType::PERCENT:
                return $product->price - floor($product->price / 100 * $product->discount_value);
            default:
                return $product->price;
        }
    }

    /**
     * @param Product $product
     */
    public function updateProductPrice(Product $product)
    {
        if (!$product->is_prices_extended) {
            $product->price_with_discount = $this->getSimplePriceWithDiscount($product);
        } else {

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
        }

        $product->save(false, ['price', 'price_with_discount']);

        $this->categoryProductsService()->afterProductParamsChanged($product);
    }
}