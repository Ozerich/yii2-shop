<?php

namespace ozerich\shop\models;

use ozerich\tools\behaviors\PriorityBehavior;

/**
 * This is the model class for table "{{%product_modules}}".
 *
 * @property int $id
 * @property int $product_id
 * @property int $priority
 * @property int $default_quantity
 * @property int $product_value_id
 * @property string $name
 * @property int $image_id
 * @property string $sku
 * @property string $note
 * @property string $params
 * @property double $price
 * @property double $price_with_discount
 * @property int $currency_id
 * @property string $discount_mode
 * @property double $discount_value
 *
 * @property Currency $currency
 * @property Image $image
 * @property Product $product
 * @property Product $productValue
 */
class ProductModule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_modules}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => PriorityBehavior::class,
                'conditionAttribute' => 'product_id'
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductValue()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_value_id']);
    }
}
