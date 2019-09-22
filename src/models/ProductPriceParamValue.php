<?php

namespace ozerich\shop\models;

use ozerich\tools\behaviors\PriorityBehavior;

/**
 * This is the model class for table "{{%product_price_param_values}}".
 *
 * @property int $id
 * @property int $product_price_param_id
 * @property string $name
 * @property int $image_id
 * @property string $description
 *
 * @property Image $image
 * @property ProductPriceParam $productPriceParam
 * @property ProductPrice[] $productPrices
 */
class ProductPriceParamValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_price_param_values}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => PriorityBehavior::class,
                'conditionAttribute' => 'product_price_param_id'
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->addOrderBy('product_price_param_values.priority ASC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPriceParam()
    {
        return $this->hasOne(ProductPriceParam::className(), ['id' => 'product_price_param_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPrices()
    {
        return $this->hasMany(ProductPrice::className(), ['param_value_id' => 'id']);
    }
}
