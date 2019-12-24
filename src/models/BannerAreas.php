<?php

namespace ozerich\shop\models;

use Yii;

/**
 * This is the model class for table "{{%banner_areas}}".
 *
 * @property int $id
 * @property string $alias
 * @property string $name
 *
 * @property Banners[] $banners
 */
class BannerAreas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%banner_areas}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alias', 'name'], 'required'],
            [['alias'], 'unique'],
            [['alias','name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'ID области',
            'name' => 'Название области',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanners()
    {
        return $this->hasMany(Banners::className(), ['area_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $this->alias = strtolower($this->alias);
        return parent::beforeSave($insert);
    }
}
