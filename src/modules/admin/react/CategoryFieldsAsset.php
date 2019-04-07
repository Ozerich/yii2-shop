<?php

namespace ozerich\shop\modules\admin\react;

use yii\web\AssetBundle;

class CategoryFieldsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/ozerich/yii2-shop/src/modules/admin/react/category-fields/build';

    public $js = [
        'build.js?v=3',
    ];

    public $css = [
        'build.css',
    ];
}