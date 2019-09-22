<?php

namespace ozerich\shop\models;

/**
 * This is the model class for table "{{%blog_posts_to_product_categories}}".
 *
 * @property int $post_id
 * @property int $category_id
 *
 * @property Category $category
 * @property BlogPost $post
 */
class BlogPostsToProductCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%blog_posts_to_product_categories}}';
    }

    public function getPost()
    {
        return $this->hasOne(BlogPost::class, ['id' => 'post_id']);
    }
}
