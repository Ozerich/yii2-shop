<?php

namespace ozerich\shop\services\products;

use ozerich\shop\models\ProductCollection;

class ProductCollectionsService
{
    public function getAllQuery()
    {
        return ProductCollection::find();
    }

    public function getById($id)
    {
        return ProductCollection::findOne($id);
    }

    public function getByAlias($alias)
    {
        return ProductCollection::find()->andWhere('url_alias=:alias', [':alias' => $alias])->one();
    }

    /**
     * @param $id
     * @return \yii\db\ActiveQuery
     */
    public function findById($id)
    {
        return ProductCollection::find()->andWhere('product_collections.id=:id', [':id' => $id]);
    }

    /**
     * @param ProductCollection $model
     * @return \ozerich\shop\models\Product[]
     */
    public function getProducts(ProductCollection $model)
    {
        return $model->products;
    }
}