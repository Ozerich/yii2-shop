<?php

namespace ozerich\shop\modules\admin\forms\blog;

use ozerich\shop\models\BlogPost;

class BlogPostForm extends BlogPost
{
    public $category_ids;

    public $same_post_ids;

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'category_ids' => 'Категории',
            'same_post_ids' => 'Похожие посты'
        ]);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['category_ids', 'same_post_ids'], 'safe']
        ]);
    }
}