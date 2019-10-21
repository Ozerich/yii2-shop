<?php

namespace ozerich\shop\services\search;

use ozerich\shop\constants\CategoryType;
use ozerich\shop\models\BlogPost;
use ozerich\shop\models\Category;
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
            ->andWhere('name LIKE :query OR sku LIKE :query', [':query' => '%' . $query . '%'])
            ->addOrderBy('popular_weight DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @param string $query
     * @return Category[]
     */
    public function searchCategories($query, $limit = 5)
    {
        return Category::find()
            ->andWhere('name LIKE :name', [':name' => '%' . $query . '%'])
            ->limit($limit)
            ->all();
    }

    /**
     * @param string $query
     * @return Category[]
     */
    public function searchPosts($query, $limit = 5)
    {
        return BlogPost::findPublished()
            ->orWhere('title LIKE :query1', [':query1' => '%' . $query . '%'])
            ->orWhere('excerpt LIKE :query2', [':query2' => '%' . $query . '%'])
            ->orWhere('content LIKE :query3', [':query3' => '%' . $query . '%'])
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