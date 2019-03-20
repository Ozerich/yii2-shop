<?php

namespace ozerich\shop\models;

/**
 * This is the model class for table "{{%field_groups}}".
 *
 * @property int $id
 * @property int $name
 * @property int $image_id
 * @property int $category_id
 *
 * @property Image $image
 * @property Category $category
 * @property Field[] $fields
 */
class FieldGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%field_groups}}';
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'image_id' => 'Картинка'
        ];
    }

    public function rules()
    {
       return [
           [['name'], 'required'],
           [['name'], 'string','max' => 255],
           [['image_id'], 'safe']
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Field::className(), ['group_id' => 'id']);
    }
}
