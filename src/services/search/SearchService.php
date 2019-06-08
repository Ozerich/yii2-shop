<?php

namespace ozerich\shop\services\search;

use ozerich\shop\models\Product;

class SearchService
{
    /**
     * @param string $query
     * @return Product[]
     */
    public function searchProducts($query, $limit = 5)
    {
        return Product::findVisibleOnSite()
            ->andWhere('name LIKE :name', [':name' => '%' . $query . '%'])
            ->addOrderBy('popular_weight DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @param string $query
     * @return Product[]
     */
    public function adminSearchProducts($query, $limit = 5)
    {
        return Product::find()
            ->andWhere('name LIKE :name', [':name' => '%' . $query . '%'])
            ->addOrderBy('popular_weight DESC')
            ->limit($limit)
            ->all();
    }
}