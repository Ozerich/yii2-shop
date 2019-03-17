<?php

namespace ozerich\shop\models;

/**
 * This is the model class for table "product_field_values".
 *
 * @property int $product_id
 * @property int $field_id
 * @property string $value
 *
 * @property Field $field
 * @property Product $product
 */
class ProductFieldValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_field_values';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Field::className(), ['id' => 'field_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
