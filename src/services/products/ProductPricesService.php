<?php

namespace ozerich\shop\services\products;

use ozerich\shop\constants\DiscountType;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductPrice;
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

    public function getPriceWithDiscount(ProductPrice $productPrice)
    {
        switch ($productPrice->discount_mode) {
            case DiscountType::FIXED:
                return $productPrice->discount_value;
            case DiscountType::AMOUNT:
                return $productPrice->value - $productPrice->discount_value;
            case DiscountType::PERCENT:
                return $productPrice->value - floor($productPrice->value / 100 * $productPrice->discount_value);
            default:
                return $productPrice->value;
        }
    }

    /**
     * @param Product $product
     */
    public function updateProductPrice(Product $product)
    {
        if (!$product->is_prices_extended) {
            $product->price_with_discount = $this->getSimplePriceWithDiscount($product);

            $product->save(false, ['price_with_discount']);
        } else {

            $paramsCount = ProductPriceParam::find()->andWhere('product_id=:product_id', [':product_id' => $product->id])->count();

            $prices = $product->prices;
            $min = null;
            $min_discount_mode = null;
            $min_discount_value = null;
            $min_discount_price = null;

            foreach ($prices as $price) {
                if ($paramsCount == 2 && !$price->param_value_second_id || !$price->param_value_id) {
                    continue;
                }

                $summaryPrice = $this->getPriceWithDiscount($price);

                if ($min === null || $summaryPrice < $min) {
                    $min = $price->value;
                    $min_discount_mode = $price->discount_mode;
                    $min_discount_value = $price->discount_value;
                    if ($price->discount_mode) {
                        $min_discount_price = $summaryPrice;
                    } else {
                        $min_discount_price = null;
                    }
                }
            }

            $product->price = $min;
            $product->discount_mode = $min_discount_mode;
            $product->discount_value = $min_discount_value;
            $product->price_with_discount = $min_discount_price;

            $product->save(false, ['price', 'price_with_discount', 'discount_mode', 'discount_value']);
        }


        $this->categoryProductsService()->afterProductParamsChanged($product);
    }
}