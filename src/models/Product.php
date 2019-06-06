<?php

namespace ozerich\shop\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $url_alias
 * @property string $type
 * @property string $name
 * @property string $label
 * @property boolean $hidden
 * @property boolean $is_new
 * @property int $image_id
 * @property int $schema_image_id
 * @property int $category_id
 * @property int $manufacture_id
 * @property float $price
 * @property string $discount_mode
 * @property float $discount_value
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
 * @property float $price_with_discount
 * @property string $stock
 * @property integer $stock_waiting_days
 * @property string $price_note
 * @property boolean $is_price_from
 * @property integer $currency_id
 * @property integer $collection_id
 *
 * @property Image $image
 * @property ProductCollection $collection
 * @property Currency $currency
 * @property Manufacture $manufacture
 * @property Image $schemaImage
 * @property Image[] $images
 * @property ProductImage[] $productImages
 * @property ProductPrice[] $prices
 * @property ProductModule[] $modules
 * @property ProductFieldValue[] $productFieldValues
 * @property ProductPriceParam[] $productPriceParams
 * @property ProductCategory[] $productCategories
 * @property Category $category
 * @property ProductSame $productSameProducts
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url_alias', 'name'], 'required'],
            [['image_id', 'popular', 'is_prices_extended', 'manufacture_id', 'category_id'], 'integer'],
            [['price'], 'number'],
            [['text'], 'safe'],
            [['url_alias', 'name', 'label'], 'string', 'max' => 255],

            [['h1_value', 'seo_title'], 'string', 'max' => 255],
            [['seo_description'], 'string'],

            [['sku', 'price_hidden_text', 'sale_disabled_text'], 'string'],
            [['price_hidden', 'sale_disabled'], 'boolean'],

            [['discount_mode'], 'string'],
            [['discount_value'], 'safe'],

            [['price_note'], 'string'],
            [['is_price_from', 'hidden', 'is_new'], 'boolean'],

            [['collection_id'], 'integer']
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
            'manufacture_id' => 'Производитель',
            'label' => 'Маркировка',
            'hidden' => 'Не отображать на сайте',
            'is_new' => 'Новинка',
            'collection_id' => 'Коллекция'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public static function findVisibleOnSite()
    {
        return self::find()->andWhere('hidden = 0');
    }

    public function behaviors()
    {
        return [
            'sitemap' => [
                'class' => SitemapBehavior::class,
                'scope' => function (ActiveQuery $model) {
                    return $model->andWhere('hidden = 0');
                },
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
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
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
    public function getManufacture()
    {
        return $this->hasOne(Manufacture::className(), ['id' => 'manufacture_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(ProductCollection::className(), ['id' => 'collection_id']);
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
    public function getModules()
    {
        return $this->hasMany(ProductModule::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories()
    {
        return $this->hasMany(ProductCategory::className(), ['product_id' => 'id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getProductSameProducts()
    {
        return $this->hasMany(ProductSame::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSameProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_same_id'])->via('productSameProducts');
    }


    /**
     * @return string
     */
    public function getUrl($absolute = false)
    {
        return Url::to('/products/' . $this->id . '-' . $this->url_alias, $absolute);
    }

    /**
     * @return string
     */
    public function getNameWithManufacture()
    {
        return $this->name . ($this->manufacture ? ' (' . $this->manufacture->name . ')' : '');
    }
}
