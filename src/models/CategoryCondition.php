<?php

namespace ozerich\shop\models;

use Yii;

/**
 * This is the model class for table "category_conditions".
 *
 * @property int $id
 * @property int $category_id
 * @property string $type
 * @property string $value
 *
 * @property Category $category
 */
class CategoryCondition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_conditions';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
