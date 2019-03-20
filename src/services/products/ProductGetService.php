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
        return Product::find()
            ->joinWith('productCategories')
            ->andWhere('product_categories.category_id =:category_id', [':category_id' => $id]);
    }
}