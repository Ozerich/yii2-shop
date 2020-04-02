<?php

namespace ozerich\shop\services\products;

use ozerich\shop\constants\DiscountType;
use ozerich\shop\constants\Stock;
use ozerich\shop\models\Currency;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductPrice;
use ozerich\shop\models\ProductPriceParam;
use ozerich\shop\models\ProductPriceParamValue;
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
            $min_price = null;
            $min_discount_mode = null;
            $min_discount_value = null;
            $min_discount_price = null;
            $best_stock = null;
            $best_stock_val = null;

            foreach ($prices as $price) {
                if ($paramsCount == 2 && !$price->param_value_second_id || !$price->param_value_id) {
                    continue;
                }

                $summaryPrice = $this->getPriceWithDiscount($price);

                if ($min === null || $summaryPrice < $min) {
                    $min = $summaryPrice;
                    $min_price = $price->value;
                    $min_discount_mode = $price->discount_mode;
                    $min_discount_value = $price->discount_value;
                    if ($price->discount_mode) {
                        $min_discount_price = $summaryPrice;
                    } else {
                        $min_discount_price = null;
                    }
                }

                if ($best_stock == null || (Stock::toInteger($price->stock) > $best_stock)) {
                    $best_stock = Stock::toInteger($price->stock);
                    $best_stock_val = $price->stock;
                }
            }

            $product->stock = $best_stock_val;
            $product->price = $min_price;
            $product->discount_mode = $min_discount_mode;
            $product->discount_value = $min_discount_value;
            $product->price_with_discount = $min_discount_price;

            $product->save(false, ['price', 'price_with_discount', 'discount_mode', 'discount_value', 'stock']);
        }

        $this->categoryProductsService()->afterProductParamsChanged($product);

        if ($product->category) {
            $this->categoryProductsService()->updateCategoryStats($product->category);
        }
    }

    private $cachePrimaryCurrencyId = 0;

    private function getPrimaryCurrencyId()
    {
        if ($this->cachePrimaryCurrencyId === 0) {
            $this->cachePrimaryCurrencyId = Currency::defaultId();
        }
        return $this->cachePrimaryCurrencyId;
    }

    private $cacheRates = null;

    private function getRate($currency)
    {
        if ($currency == $this->getPrimaryCurrencyId()) {
            return 1;
        }

        if ($this->cacheRates === null) {
            $rates = [];

            /** @var Currency[] $currencies */
            $currencies = Currency::find()->all();
            foreach ($currencies as $_currency) {
                $rates[$_currency->id] = $_currency->rate;
            }

            $this->cacheRates = $rates;
        }

        return isset($this->cacheRates[$currency]) ? $this->cacheRates[$currency] : 1;
    }

    public function preparePriceForOutput($price, $currencyId)
    {
        $rate = $this->getRate($currencyId);

        return round($price * $rate, 2);
    }

    /**
     * @param Product $product
     */
    public function bindNewProductPrices(Product $product){
        $priceParams = $product->productPriceParams;
        if(count($priceParams) === 1) {
            foreach ($priceParams as $priceParam) {
                foreach ($priceParam->productPriceParamValues as $productPriceParamValue) {
                    $price = ProductPrice::find()
                        ->where([
                            'product_id' => $product->id,
                            'param_value_id' => $productPriceParamValue->id
                        ])->andWhere(['is', 'param_value_second_id', null])->one();
                    if(!$price) {
                        $price = new ProductPrice();
                        $price->product_id = $product->id;
                        $price->param_value_id = $productPriceParamValue->id;
                        $price->save();
                    }
                }
            }
        } elseif(count($priceParams) === 2) {
            foreach ($priceParams as $priceParam) {
                foreach ($priceParam->productPriceParamValues as $productPriceParamValue) {
                    foreach ($priceParams as $priceParam2) {
                        if($priceParam2->id !== $priceParam->id) {
                            foreach ($priceParam2->productPriceParamValues as $productPriceParamValue2) {
                                $price = ProductPrice::find()
                                    ->where([
                                        'product_id' => $product->id,
                                        'param_value_id' => $productPriceParamValue->id,
                                        'param_value_second_id' => $productPriceParamValue2->id,
                                    ])
                                    ->orWhere([
                                        'product_id' => $product->id,
                                        'param_value_second_id' => $productPriceParamValue->id,
                                        'param_value_id' => $productPriceParamValue2->id,
                                    ])
                                    ->one();
                                if(!$price) {
                                    $price = new ProductPrice();
                                    $price->product_id = $product->id;
                                    $price->param_value_id = $productPriceParamValue->id;
                                    $price->param_value_second_id = $productPriceParamValue2->id;
                                    $price->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function createNewProductPrices($product, $param){

    }
}
