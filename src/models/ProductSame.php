<?php

namespace ozerich\shop\models;

/**
 * This is the model class for table "{{%product_same}}".
 *
 * @property int $product_id
 * @property int $product_same_id
 *
 * @property Product $product
 * @property Product $productSame
 */
class ProductSame extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_same}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductSame()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_same_id']);
    }
}
