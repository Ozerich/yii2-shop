<?php

namespace ozerich\shop\models;

use ozerich\tools\behaviors\PriorityBehavior;

/**
 * This is the model class for table "{{%product_price_params}}".
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 *
 * @property ProductPriceParamValue[] $productPriceParamValues
 * @property Product $product
 */
class ProductPriceParam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_price_params}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPriceParamValues()
    {
        return $this->hasMany(ProductPriceParamValue::className(), ['product_price_param_id' => 'id']);
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
    public static function find()
    {
        return parent::find()->addOrderBy('product_price_params.priority ASC');
    }

    public function behaviors()
    {
        return [
            [
                'class' => PriorityBehavior::class,
                'conditionAttribute' => 'priority'
            ]
        ];
    }
}
