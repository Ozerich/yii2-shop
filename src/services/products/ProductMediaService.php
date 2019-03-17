<?php

namespace ozerich\shop\services\products;

use ozerich\shop\models\Product;
use ozerich\shop\models\ProductImage;

class ProductMediaService
{
    public function setProductImages(Product $product, $imageIds)
    {
        ProductImage::deleteAll([
            'product_id' => $product->id
        ]);

        foreach ($imageIds as $id) {
            $item = new ProductImage();
            $item->product_id = $product->id;
            $item->image_id = $id;
            $item->save();
        }
    }
}