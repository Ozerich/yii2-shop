<?php

namespace ozerich\shop\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use ozerich\tools\utils\Translit;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%product_collections}}".
 *
 * @property int $id
 * @property string $url_alias
 * @property string $title
 * @property string $content
 * @property int $image_id
 * @property int $manufacture_id
 * @property string $seo_title
 * @property string $seo_description
 *
 * @property Image $image
 * @property Manufacture $manufacture
 * @property Product[] $products
 */
class ProductCollection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_collections}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url_alias', 'title'], 'required'],
            [['url_alias'], 'unique'],
            [['content', 'seo_description'], 'string'],
            [['image_id', 'manufacture_id'], 'integer'],
            [['url_alias', 'title', 'seo_title'], 'string', 'max' => 255],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['image_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url_alias' => 'URL-алиас',
            'title' => 'Название',
            'content' => 'Содержание',
            'image_id' => 'Картинка',
            'seo_title' => 'Заголовок страницы (тег title)',
            'seo_description' => 'SEO - описание',
            'manufacture_id' => 'Производитель',
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
                        'priority' => 0.8
                    ];
                }
            ],
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
    public function getManufacture()
    {
        return $this->hasOne(Manufacture::className(), ['id' => 'manufacture_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['collection_id' => 'id']);
    }

    public function beforeValidate()
    {
        if (empty($this->url_alias)) {
            $this->url_alias = Translit::convert($this->title);
        }
        return parent::beforeValidate();
    }

    /**
     * @param bool $absolute
     * @return string
     */
    public function getUrl($absolute = false)
    {
        return Url::to('/collection/' . $this->url_alias, $absolute);
    }

    /**
     * @return string[]
     */
    public static function getList()
    {
        /** @var self[] $items */
        $items = self::find()->all();

        $result = [];
        foreach ($items as $item) {
            $result[$item->id] = $item->title;
        }

        return $result;
    }
}
