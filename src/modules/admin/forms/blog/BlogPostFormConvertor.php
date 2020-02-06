<?php

namespace ozerich\shop\modules\admin\forms\blog;

use ozerich\shop\models\BlogPost;
use ozerich\shop\models\BlogPostSame;
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

        $form->same_post_ids = $post->samePosts;

        return $form;
    }

    public function saveModelFromForm(BlogPost $model, BlogPostForm $form)
    {
        $model->attributes = $form->attributes;

        BlogPostsToProductCategories::deleteAll(['post_id' => $model->id]);

        if(is_array($form->category_ids)) {
            foreach ($form->category_ids as $category_id) {
                $item = new BlogPostsToProductCategories();
                $item->post_id = $model->id;
                $item->category_id = $category_id;
                $item->save();
            }
        }


        $model->save();

        BlogPostSame::deleteAll('post_id=:post_id OR same_post_id=:post_id', [':post_id' => $model->id]);

        if ($form->same_post_ids) {
            foreach ($form->same_post_ids as $productId) {
                $sameModel = new BlogPostSame();
                $sameModel->post_id = $model->id;
                $sameModel->same_post_id = $productId;
                $sameModel->save();

                $sameModel = new BlogPostSame();
                $sameModel->post_id = $productId;
                $sameModel->same_post_id = $model->id;
                $sameModel->save();
            }
        }

        return true;
    }
}
