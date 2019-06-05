<?php

namespace ozerich\shop\modules\admin\react;

use yii\web\AssetBundle;

class ProductParamsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/ozerich/yii2-shop/src/modules/admin/react/product-params/build';

    public $js = [
        'build.js',
    ];

    public $css = [
        'build.css',
    ];
}