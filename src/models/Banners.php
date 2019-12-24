<?php

namespace ozerich\shop\models;

use ozerich\filestorage\models\File;
use ozerich\tools\behaviors\PriorityBehavior;
use Yii;

/**
 * This is the model class for table "{{%banners}}".
 *
 * @property int $id
 * @property int $area_id
 * @property int $photo_id
 * @property int $url
 * @property int $title
 * @property int $text
 * @property int $priority
 *
 * @property BannerAreas $area
 * @property Files $photo
 */
class Banners extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%banners}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area_id', 'photo_id'], 'required'],
            [['area_id', 'photo_id', 'priority'], 'integer'],
            [['url', 'title'], 'string', 'max' => 255],
            [['text'], 'string'],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerAreas::className(), 'targetAttribute' => ['area_id' => 'id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['photo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'area_id' => 'Зона размещения',
            'photo_id' => 'Изображение',
            'url' => 'Ссылка',
            'title' => 'Заголовок',
            'text' => 'Текст',
        ];
    }


    public function behaviors()
    {
        return [
            [
                'class' => PriorityBehavior::class,
                'attribute' => 'priority',
                'conditionAttribute' => ['area_id']
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(BannerAreas::className(), ['id' => 'area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(File::className(), ['id' => 'photo_id']);
    }
}
