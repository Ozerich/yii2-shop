<?php

namespace ozerich\shop\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%blog_posts}}".
 *
 * @property int $id
 * @property string $url_alias
 * @property int $category_id
 * @property int $image_id
 * @property string $title
 * @property string $excerpt
 * @property string $content
 * @property string $page_title
 * @property string $meta_description
 *
 * @property BlogCategory $category
 * @property Image $image
 */
class BlogPost extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%blog_posts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url_alias', 'category_id', 'title'], 'required'],
            [['category_id', 'image_id'], 'integer'],
            [['excerpt', 'content', 'meta_description'], 'string'],
            [['url_alias', 'title', 'page_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url_alias' => 'URL',
            'category_id' => 'Категория',
            'image_id' => 'Картинка',
            'title' => 'Заголовок',
            'excerpt' => 'Краткое описание',
            'content' => 'Содержание',
            'page_title' => 'Заголовок страницы',
            'meta_description' => 'SEO описание',
        ];
    }

    public function behaviors()
    {
        return [
            'sitemap' => [
                'class' => SitemapBehavior::class,
                'dataClosure' => function (self $model) {
                    return [
                        'loc' => $model->getUrl(true),
                        'lastmod' => time() - 86400,
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.6
                    ];
                }
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(BlogCategory::className(), ['id' => 'category_id']);
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
    public static function findByParent(?BlogCategory $category = null)
    {
        if ($category === null) {
            return self::find()->andWhere('category_id is null');
        }

        return self::find()->andWhere('category_id = :parent_id', [':parent_id' => $category->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findByAlias($alias)
    {
        return self::find()->andWhere('url_alias=:alias', [':alias' => $alias]);
    }

    public function getUrl($absolute = false)
    {
        return Url::to('/blog/' . $this->url_alias, $absolute);
    }
}
