<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;

class ProductSeoForm extends Product
{
    public $h1_value;

    public $seo_title;

    public $seo_description;

    public function activeAttributes()
    {
        return ['h1_value', 'seo_title', 'seo_description'];
    }
}