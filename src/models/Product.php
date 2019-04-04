<?php

namespace ozerich\shop\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $url_alias
 * @property string $name
 * @property int $image_id
 * @property int $schema_image_id
 * @property int $price
 * @property string $text
 * @property string $video
 * @property boolean $popular
 * @property boolean $is_prices_extended
 * @property string $h1_value
 * @property string $seo_title
 * @property string $seo_description
 * @property integer $popular_weight
 * @property string $sku
 * @property boolean $sale_disabled
 * @property string $sale_disabled_text
 * @property boolean $price_hidden
 * @property string $price_hidden_text
 *
 * @property Image $image
 * @property Image $schemaImage
 * @property Image[] $images
 * @property ProductImage $productImages
 * @property ProductPrice $prices
 * @property ProductFieldValue[] $productFieldValues
 * @property ProductPriceParam[] $productPriceParams
 * @property ProductCategory[] $productCategories
 * @property Category[] $category
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url_alias', 'name'], 'required'],
            [['image_id', 'price', 'popular', 'is_prices_extended'], 'integer'],
            [['text'], 'safe'],
            [['url_alias', 'name'], 'string', 'max' => 255],

            [['h1_value', 'seo_title'], 'string', 'max' => 255],
            [['seo_description'], 'string'],

            [['sku', 'price_hidden_text', 'sale_disabled_text'], 'string'],
            [['price_hidden', 'sale_disabled'], 'boolean']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url_alias' => 'URL алиас',
            'name' => 'Название',
            'image_id' => 'Картинка',
            'schema_image_id' => 'Картинка - схема',
            'price' => 'Цена',
            'text' => 'Текстовое описание',
            'popular' => 'Популярный товар',
            'is_prices_extended' => 'Расширенный режим цен',
            'h1_value' => 'Значение H1',
            'seo_title' => 'Заголовок страницы',
            'seo_description' => 'META описание',
            'sku' => 'Артикул',
            'price_hidden' => 'Цена недоступна',
            'price_hidden_text' => 'Текст вместо цены',
            'sale_disabled' => 'Заказ товара недоступен',
            'sale_disabled_text' => 'Причина, по которой недоступен заказ',
        ];
    }

    public function behaviors()
    {
        return [
            'sitemap' => [
                'class' => SitemapBehavior::class,
                'dataClosure' => function (self $model) {
                    return [
                        'loc' => Url::to($model->getUrl(), true),
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchemaImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'schema_image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductFieldValues()
    {
        return $this->hasMany(ProductFieldValue::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(ProductPrice::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPriceParams()
    {
        return $this->hasMany(ProductPriceParam::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::class, ['id' => 'image_id'])->via('productImages');
    }

    /**
     * @return string
     */
    public function getUrl($absolute = false)
    {
        return Url::to('/products/' . $this->id . '-' . $this->url_alias, $absolute);
    }
}
