<?php

namespace ozerich\shop\models;

/**
 * This is the model class for table "{{%currencies}}".
 *
 * @property int $id
 * @property string $name
 * @property string $full_name
 * @property double $rate
 * @property int $primary
 *
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%currencies}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'full_name'], 'required'],
            [['rate'], 'number'],
            [['primary'], 'integer'],
            [['name', 'full_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Обозначение',
            'full_name' => 'Полное имя',
            'rate' => 'Курс',
            'primary' => 'Основная валюта',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->primary) {
            Currency::updateAll(['primary' => 0]);
            $this->rate = 1;
        }

        return parent::beforeSave($insert);
    }

}
