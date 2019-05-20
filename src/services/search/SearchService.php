<?php

namespace ozerich\shop\services\search;

use ozerich\shop\models\Product;

class SearchService
{
    /**
     * @param string $query
     * @return Product[]
     */
    public function searchProducts($query)
    {
        return Product::findVisibleOnSite()
            ->andWhere('name LIKE :name', [':name' => '%' . $query . '%'])
            ->addOrderBy('popular_weight DESC')
            ->limit(5)
            ->all();
    }
}