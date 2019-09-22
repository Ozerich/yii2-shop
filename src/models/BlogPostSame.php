<?php

namespace ozerich\shop\models;

/**
 * This is the model class for table "{{%product_same}}".
 *
 * @property int $post_id
 * @property int $same_post_id
 *
 * @property BlogPost $post
 * @property BlogPost $post_same
 */
class BlogPostSame extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%blog_posts_same}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(BlogPost::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostSame()
    {
        return $this->hasOne(BlogPost::className(), ['id' => 'same_post_id']);
    }
}
