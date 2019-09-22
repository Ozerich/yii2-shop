<?php

namespace ozerich\shop\modules\admin\forms\blog;

use ozerich\shop\models\BlogPost;
use ozerich\shop\models\BlogPostsToProductCategories;
use ozerich\shop\traits\ServicesTrait;
use yii\base\Model;

class BlogPostFormConvertor extends Model
{
    use ServicesTrait;

    public function loadFormFromModel(BlogPost $post)
    {
        $form = new BlogPostForm();

        $form->attributes = $post->attributes;

        $form->category_ids = BlogPostsToProductCategories::find()
            ->andWhere('post_id=:post_id', [':post_id' => $post->id])
            ->select('category_id')->column();

        return $form;
    }

    public function saveModelFromForm(BlogPost $model, BlogPostForm $form)
    {
        $model->attributes = $form->attributes;

        BlogPostsToProductCategories::deleteAll(['post_id' => $model->id]);

        foreach ($form->category_ids as $category_id) {
            $item = new BlogPostsToProductCategories();
            $item->post_id = $model->id;
            $item->category_id = $category_id;
            $item->save();
        }

        $model->save();

        return true;
    }
}