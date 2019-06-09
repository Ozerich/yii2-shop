<?php

namespace ozerich\shop\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use ozerich\shop\traits\ServicesTrait;
use ozerich\tools\utils\Translit;
use yii\helpers\Url;

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
 * @property string $h1_value
 * @property string $seo_title
 * @property string $seo_description
 * @property string $type
 * @property boolean $home_display
 * @property integer $home_position
 * @property string $seo_title_products_template
 * @property string $seo_description_products_template
 * @property integer $products_count
 * @property float $min_price
 * @property float $max_price
 *
 * @property Image $image
 * @property Category $parent
 * @property Category[] $categories
 * @property Field[] $fields
 * @property CategoryCondition[] $conditions
 * @property CategoryField[] $categoryFields
 * @property Category[] $categoryDisplayCategories
 * @property Category[] $displayedCategories
 * @property CategoryManufacture[] $categoryManufactures
 * @property Manufacture[] $manufactures
 */
class Category extends \yii\db\ActiveRecord
{
    use ServicesTrait;

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

            [['url_alias'], 'filter', 'filter' => 'trim'],

            [['h1_value', 'seo_title', 'type'], 'string', 'max' => 255],
            [['seo_description'], 'string'],

            [['seo_description_products_template', 'seo_title_products_template'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
            'parent_id' => 'Родительская категория',
            'url_alias' => 'URL алиас',
            'name' => 'Имя',
            'image_id' => 'Картинка',
            'image' => 'Картинка',
            'text' => 'Текст',
            'h1_value' => 'Значение H1',
            'seo_title' => 'Заголовок страницы',
            'seo_description' => 'META описание',
            'seo_description_products_template' => 'Шаблон SEO Description для товаров',
            'seo_title_products_template' => 'Шаблон заголовка страниц для товаров',
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
    public function getConditions()
    {
        return $this->hasMany(CategoryCondition::className(), ['category_id' => 'id']);
    }

    public function getDisplayedCategories()
    {
        return $this->hasMany(CategoryDisplay::className(), ['parent_id' => 'id']);
    }

    public function getCategoryDisplayCategories()
    {
        return $this->hasMany(CategoryDisplay::className(), ['category_id' => 'id']);
    }


    public function getCategoryManufactures()
    {
        return $this->hasMany(CategoryManufacture::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufactures()
    {
        return $this->hasMany(Manufacture::class, ['id' => 'manufacture_id'])->via('categoryManufactures');
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryFields()
    {
        return $this->hasMany(CategoryField::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Field::className(), ['category_id' => 'id']);
    }


    public function beforeValidate()
    {
        if (empty($this->url_alias)) {
            $this->url_alias = Translit::convert($this->name);
        }

        return parent::beforeValidate();
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
    public static function findByParent(?Category $parent)
    {
        if ($parent === null) {
            return self::findRoot();
        }

        return self::find()->andWhere('parent_id = :parent_id', [':parent_id' => $parent->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findByAlias($alias)
    {
        return self::find()->andWhere('url_alias=:alias', [':alias' => $alias]);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ((isset($changedAttributes['parent_id']) && $changedAttributes['parent_id'] !== $this->parent_id) || $insert) {
            $this->categoriesService()->updateCategoryLevel($this);
        }

        return parent::afterSave($insert, $changedAttributes);
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

    /**
     * @return string
     */
    public function getFullName()
    {
        $items = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            $items[] = $parent->name;
            $parent = $parent->parent;
        }

        return implode(' ----> ', array_reverse($items));
    }

    public function getUrl($absolute = false)
    {
        $items = [$this->url_alias];
        $parent = $this->parent;

        while ($parent) {
            $items[] = $parent->url_alias;
            $parent = $parent->parent;
        }

        return Url::to('/catalog/' . implode('/', array_reverse($items)), $absolute);
    }

    /**
     * @return int
     */
    public function getProductsCount()
    {
        $ids = array_merge([$this->id], Category::findByParent($this)->select('id')->column());

        $result = [];

        foreach ($ids as $id) {
            $result = array_merge($result, $this->productGetService()->getSearchByCategoryQuery($id)->select('products.id')->column());
        }

        return count(array_unique($result));
    }
}
