<?php

namespace ozerich\shop\plugins;

use ozerich\shop\models\Product;

interface IProductTab
{
    public function tabLabel();

    public function isTabVisible(Product $product);

    public function render(Product $product);
}