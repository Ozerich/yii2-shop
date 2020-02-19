<?php

namespace ozerich\shop\services\products;

use ozerich\shop\models\Product;
use ozerich\shop\models\ProductImage;

class ProductMediaService
{
    public function setProductImages(Product $product, $imageIds, $imageTexts = [])
    {
        $priority = $imageIds;
        $saveProductImageIds = [];
        foreach ($imageIds as $key => $imageId) {
            $productImage = ProductImage::find()
                ->where(['product_id' => $product->id])
                ->andWhere(['image_id' => $imageId])->one();
            if($productImage) {
                $saveProductImageIds [] = $productImage->id;
                $productImage->text = isset($imageTexts[$imageId]) ? $imageTexts[$imageId] : null;
                $productImage->priority = array_search($imageId, $priority) + 1;
                $productImage->save();
                unset($imageIds[$key]);
            }
        }

        $delete = ProductImage::find()
            ->where(['not in', 'id', $saveProductImageIds])
            ->andWhere([ 'product_id' => $product->id,])
            ->select('id')->column();
        ProductImage::deleteAll(['id' => $delete]);


        foreach ($imageIds as $id) {
            $item = new ProductImage();
            $item->product_id = $product->id;
            $item->image_id = $id;
            $item->text = isset($imageTexts[$id]) ? $imageTexts[$id] : null;
            $item->priority = array_search($id, $priority) + 1;
            $item->save();
        }
    }
}
