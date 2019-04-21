<?php

namespace ozerich\shop\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $option
 * @property string $value
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    /**
     * @param $option
     * @return ActiveQuery
     */
    public static function findByOption($option)
    {
        return self::find()->andWhere('`option` = :option', [':option' => $option]);
    }
}
