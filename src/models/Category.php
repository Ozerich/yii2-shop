<?php

namespace ozerich\shop\models;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $level
 * @property string $url_alias
 * @property string $name
 * @property string $image_id
 * @property string $text
 *
 * @property Image $image
 * @property Category $parent
 * @property Category[] $categories
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'image_id'], 'integer'],
            [['url_alias', 'name'], 'required'],
            [['url_alias', 'name'], 'string', 'max' => 255],
            [['text'], 'safe'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Родительская категория',
            'url_alias' => 'URL алиас',
            'name' => 'Имя',
            'image_id' => 'Картинка',
            'image' => 'Картинка',
            'text' => 'Текст'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
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
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findRoot()
    {
        return self::find()->andWhere('parent_id is null');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findByParent(Category $parent)
    {
        return self::find()->andWhere('parent_id = :parent_id', [':parent_id' => $parent->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findByAlias($alias)
    {
        return self::find()->andWhere('url_alias=:alias', [':alias' => $alias]);
    }

    /**
     * @return self[]
     */
    public static function getTree()
    {
        /** @var Category[] $roots */
        $roots = self::findRoot()->all();

        $result = [];
        foreach ($roots as $root) {
            $result[] = $root;
            $result = array_merge($result, self::find()->andWhere('parent_id = :parent_id', [':parent_id' => $root->id])->all());
        }

        return $result;
    }

    public function getProductsCount()
    {
        return Product::find()->andWhere('category_id=:category_id', [':category_id' => $this->id])->count();
    }
}
