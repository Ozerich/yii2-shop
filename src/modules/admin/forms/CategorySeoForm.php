<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Category;

class CategorySeoForm extends Category
{
    public $h1_value;

    public $seo_title;

    public $seo_description;

    public function activeAttributes()
    {
        return ['h1_value', 'seo_title', 'seo_description'];
    }
}