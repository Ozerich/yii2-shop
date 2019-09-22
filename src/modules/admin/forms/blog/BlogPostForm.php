<?php

namespace ozerich\shop\modules\admin\forms\blog;

use ozerich\shop\models\BlogPost;
use ozerich\shop\models\Category;

class BlogPostForm extends BlogPost
{
    public $category_ids;

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'category_ids' => 'Категории'
        ]);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['category_ids', 'safe']
        ]);
    }
}