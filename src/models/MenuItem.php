<?php

namespace ozerich\shop\models;

use ozerich\tools\behaviors\PriorityBehavior;

/**
 * This is the model class for table "menu_items".
 *
 * @property int $id
 * @property int $menu_id
 * @property int $parent_id
 * @property string $title
 * @property string $url
 * @property int $priority
 *
 * @property Menu $menu
 */
class MenuItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%menu_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'menu_id', 'priority', 'parent_id'], 'integer'],
            [['menu_id', 'title'], 'required'],
            [['title', 'url'], 'string', 'max' => 255],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => 'Меню',
            'parent_id' => 'Родительский пункт',
            'title' => 'Название',
            'url' => 'Ссылка'
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => PriorityBehavior::class,
                'attribute' => 'priority',
                'conditionAttribute' => ['menu_id', 'parent_id']
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->addOrderBy('priority ASC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findByMenu(Menu $menu)
    {
        return self::find()->andWhere('menu_id=:menu_id', [':menu_id' => $menu->id]);
    }

    /**
     * @param Menu $menu
     * @return \yii\db\ActiveQuery
     */
    public static function findRoot(Menu $menu)
    {
        return self::findByMenu($menu)->andWhere('parent_id is null');
    }
}
