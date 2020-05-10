<?php

namespace ozerich\shop\services\products;

use ozerich\shop\models\Product;
use ozerich\shop\models\ProductImage;

class ProductMediaService
{
    public function setProductImages(Product $product, $imageIds, $imageTexts = [])
    {
        $saveProductImageIds = [];

        $priority = 1;

        foreach ($imageIds as $key => $imageId) {
            $productImage = ProductImage::find()
                ->where(['product_id' => $product->id])
                ->andWhere(['image_id' => $imageId])->one();

            if ($productImage) {
                $saveProductImageIds [] = $productImage->id;
                $productImage->text = isset($imageTexts[$imageId]) ? $imageTexts[$imageId] : null;
                $productImage->priority = $priority++;
                $productImage->save();
                unset($imageIds[$key]);
            } else {
                $item = new ProductImage();
                $item->product_id = $product->id;
                $item->image_id = $imageId;
                $item->text = isset($imageTexts[$imageId]) ? $imageTexts[$imageId] : null;
                $item->save();

                $item->priority = $priority++;
                $item->save(false, ['priority']);

                $saveProductImageIds [] = $item->id;
            }
        }

        $delete = ProductImage::find()
            ->where(['not in', 'id', $saveProductImageIds])
            ->andWhere(['product_id' => $product->id,])
            ->select('id')
            ->column();

        ProductImage::deleteAll(['id' => $delete]);
    }
}
