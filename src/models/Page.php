<?php

namespace ozerich\shop\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property int $id
 * @property string $url
 * @property string $title
 * @property string $content
 * @property string $meta_title
 * @property string $meta_description
 * @property int $meta_image_id
 *
 * @property Image $metaImage
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'title'], 'required'],
            [['url'], 'unique'],
            [['content'], 'string'],
            [['meta_image_id'], 'integer'],
            [['url', 'title', 'meta_title', 'meta_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'URL',
            'title' => 'Заголовок',
            'content' => 'Содержание',
            'meta_title' => 'Заголовок страницы',
            'meta_description' => 'Meta Description',
            'meta_image_id' => 'Meta Картинка',
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
                        'priority' => 0.5
                    ];
                }
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'meta_image_id']);
    }

    public function beforeSave($insert)
    {
        if (mb_substr($this->url, 0, 1) !== '/') {
            $this->url = '/' . $this->url;
        }

        return parent::beforeSave($insert);
    }

    public function getUrl($absolute = false)
    {
        return Url::to($this->url, $absolute);
    }
}
