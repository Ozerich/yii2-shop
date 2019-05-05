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
        $category = $product->category;

        $baseQuery = Product::findVisibleOnSite()
            ->andWhere('category_id=:category_id', [':category_id' => $category->id])
            ->andWhere('products.id <> :id', [':id' => $product->id]);

        $query = clone $baseQuery;

        $items = $query->andWhere('popular_weight >= :weight', [':weight' => $product->popular_weight])
            ->limit(10)
            ->all();

        $ids = array_map(function (Product $product) {
            return $product->id;
        }, $items);

        if (count($ids) < 10) {
            $query = clone $baseQuery;
            $query->andWhere('popular_weight <= :weight', [':weight' => $product->popular_weight]);

            if (!empty($ids)) {
                $query->andWhere('id not in (' . implode(',', $ids) . ')');
            }

            $items = array_merge($items, $query->limit(10 - count($ids))->all());
        }

        return $items;
    }
}