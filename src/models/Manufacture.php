<?php

namespace ozerich\shop\models;

use yii\helpers\Url;

/**
 * This is the model class for table "manufactures".
 *
 * @property int $id
 * @property string $name
 * @property string $url_alias
 * @property int $image_id
 * @property string $content
 * @property string $seo_title
 * @property string $seo_description
 *
 * @property Image $image
 * @property Product[] $products
 */
class Manufacture extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manufactures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url_alias'], 'required'],
            [['image_id'], 'integer'],
            [['content', 'seo_description'], 'string'],
            [['name', 'url_alias'], 'string', 'max' => 255],
            [['seo_title'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'url_alias' => 'URL-алиас',
            'image_id' => 'Картинка',
            'content' => 'Текст',
            'seo_title' => 'Заголовок страницы',
            'seo_description' => 'SEO описание',
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
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['manufacture_id' => 'id']);
    }

    /**
     * @param bool $absolute
     * @return string
     */
    public function getUrl($absolute = false)
    {
        return Url::to('/brands/' . $this->url_alias, $absolute);
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
            $result[$item->id] = $item->name;
        }

        return $result;
    }
}
