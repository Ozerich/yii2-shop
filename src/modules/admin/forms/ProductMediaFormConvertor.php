<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;
use ozerich\shop\models\ProductImage;
use yii\base\Model;

class ProductMediaFormConvertor extends Model
{
    public function loadFormFromModel(Product $product)
    {
        $form = new ProductMediaForm();

        $form->video = $product->video;
        $form->schema_image_id = $product->schema_image_id;

        /** @var ProductImage[] $images */
        $images = ProductImage::find()
            ->andWhere('product_id=:product_id', [':product_id' => $product->id])
            ->all();

        $image_ids = [];
        $image_texts = [];
        foreach ($images as $image) {
            $image_ids[] = $image->image_id;
            $image_texts[$image->image_id] = $image->text;
        }

        $form->images = $image_ids;
        $form->image_texts = $image_texts;

        return $form;
    }
}
