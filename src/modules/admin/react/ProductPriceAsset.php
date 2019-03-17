<?php

namespace ozerich\shop\modules\admin\react;

use yii\web\AssetBundle;

class ProductPriceAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/react/product-price/build';

    public $js = [
        'build.js?v=2',
    ];

    public $css = [
        'build.css',
    ];
}