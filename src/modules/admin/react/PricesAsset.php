<?php

namespace ozerich\shop\modules\admin\react;

use yii\web\AssetBundle;

class PricesAsset extends AssetBundle
{
    public $sourcePath = '@vendor/ozerich/yii2-shop/src/modules/admin/react/prices/build';

    public $js = [
        'build.js?v=5',
    ];

    public $css = [
        'build.css',
    ];
}