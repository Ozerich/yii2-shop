<?php

namespace ozerich\shop\models;

use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%blog_categories}}".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $url_alias
 * @property string $description
 * @property string $name
 * @property int $image_id
 * @property string $page_title
 * @property string $meta_description
 *
 * @property Image $image
 * @property BlogCategory $parent
 * @property BlogCategory[] $blogCategories
 */
class BlogCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%blog_categories}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'image_id'], 'integer'],
            [['name', 'page_title'], 'required'],
            [['meta_description', 'description'], 'string'],
            [['url_alias', 'name', 'page_title'], 'string', 'max' => 255],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['image_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => BlogCategory::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'url_alias' => 'URL-алиас',
            'name' => 'Название',
            'image_id' => 'Картинка',
            'description' => 'Описание',
            'page_title' => 'Заголовок страницы',
            'meta_description' => 'SEO описание',
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
    public function getParent()
    {
        return $this->hasOne(BlogCategory::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogCategories()
    {
        return $this->hasMany(BlogCategory::className(), ['parent_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public static function findRoot()
    {
        return self::find()->andWhere('parent_id is null');
    }

    /**
     * @param BlogCategory $category
     * @return ActiveQuery
     */
    public static function findByParent(BlogCategory $category)
    {
        return self::find()->andWhere('parent_id = :parent_id', [':parent_id' => $category->id]);
    }

    /**
     * @return int
     */
    public function getUrl($absolute = false)
    {
        $items = [];
        $parent = $this;
        while ($parent) {
            $items[] = $parent->url_alias;
            $parent = $parent->parent;
        }

        $items = array_reverse($items);

        return Url::to('/blog/' . implode('/', $items), $absolute);
    }
}
