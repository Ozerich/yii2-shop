<?php

namespace ozerich\shop\models;

use yii\helpers\Url;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property int $category_id
 * @property string $url_alias
 * @property string $name
 * @property int $image_id
 * @property int $price
 * @property string $text
 * @property string $video
 * @property boolean $popular
 * @property boolean $is_prices_extended
 *
 * @property Category $category
 * @property Image $image
 * @property Image[] $images
 * @property ProductImage $productImages
 * @property ProductPrice $prices
 * @property ProductFieldValue[] $productFieldValues
 * @property ProductPriceParam[] $productPriceParams
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
            [['category_id', 'url_alias', 'name'], 'required'],
            [['category_id', 'image_id', 'price', 'popular', 'is_prices_extended'], 'integer'],
            [['text'], 'safe'],
            [['url_alias', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'url_alias' => 'URL алиас',
            'name' => 'Название',
            'image_id' => 'Картинка',
            'price' => 'Цена',
            'text' => 'Текстовое описание',
            'popular' => 'Популярный товар',
            'is_prices_extended' => 'Расширенный режим цен'
        ];
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
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
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
