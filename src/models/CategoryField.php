<?php

namespace ozerich\shop\models;

/**
 * This is the model class for table "category_fields".
 *
 * @property int $category_id
 * @property int $field_id
 *
 * @property Category $category
 * @property Field $field
 */
class CategoryField extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_fields';
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
    public function getField()
    {
        return $this->hasOne(Field::className(), ['id' => 'field_id']);
    }
}
