<?php

namespace ozerich\shop\services\products;

use ozerich\shop\models\Product;
use yii\db\ActiveQuery;

class ProductGetService
{
    /**
     * @param $id
     * @return ActiveQuery
     */
    public function getSearchByCategoryQuery($id)
    {
        return Product::findVisibleOnSite()->joinWith('productCategories')
            ->andWhere('product_categories.category_id=:category_id', [':category_id' => $id])
            ->addOrderBy('products.popular_weight DESC')
            ->addOrderBy('products.name ASC');
    }

    /**
     * @param $product
     * @return Product[]
     */
    public function getSameProducts(Product $product)
    {
        return $product->sameProducts;
    }
}