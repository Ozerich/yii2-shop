<?php

namespace ozerich\shop\models;

/**
 * This is the model class for table "category_display".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $category_id
 * @property int $position
 *
 * @property Category $category
 * @property Category $parent
 */
class CategoryDisplay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_display';
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
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }
}
