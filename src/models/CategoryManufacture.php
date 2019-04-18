<?php

namespace ozerich\shop\models;

/**
 * This is the model class for table "category_manufactures".
 *
 * @property int $category_id
 * @property int $manufacture_id
 *
 * @property Category $category
 * @property Manufacture $manufacture
 */
class CategoryManufacture extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_manufactures';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacture()
    {
        return $this->hasOne(Manufacture::className(), ['id' => 'manufacture_id']);
    }
}
