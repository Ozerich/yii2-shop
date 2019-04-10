<?php

namespace ozerich\shop\models;

use ozerich\tools\behaviors\PriorityBehavior;

/**
 * This is the model class for table "product_images".
 *
 * @property int $id
 * @property int $product_id
 * @property int $image_id
 * @property string $text
 * @property int $priority
 *
 * @property Image $image
 * @property Product $product
 */
class ProductImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_images}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => PriorityBehavior::class,
                'attribute' => 'priority',
                'conditionAttribute' => 'product_id'
            ]
        ];
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
    public static function find()
    {
        return parent::find()->addOrderBy('product_images.priority ASC');
    }
}
