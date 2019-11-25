<?php

namespace ozerich\shop\modules\admin\react;

use yii\web\AssetBundle;

class ProductPriceAsset extends AssetBundle
{
    public $sourcePath = '@vendor/ozerich/yii2-shop/src/modules/admin/react/product-price/build';

    public $js = [
        'build.js?v=6',
    ];

    public $css = [
        'build.css',
    ];
}