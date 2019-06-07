<?php

namespace ozerich\shop\models;

use Yii;

/**
 * This is the model class for table "{{%product_module_images}}".
 *
 * @property int $id
 * @property int $product_module_id
 * @property int $image_id
 *
 * @property Image $image
 * @property ProductModule $productModule
 */
class ProductModuleImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_module_images}}';
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
    public function getProductModule()
    {
        return $this->hasOne(ProductModule::className(), ['id' => 'product_module_id']);
    }
}
