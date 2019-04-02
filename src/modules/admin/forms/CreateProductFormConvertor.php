<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Product;
use ozerich\shop\models\ProductCategory;
use ozerich\shop\models\ProductImage;
use ozerich\tools\utils\Translit;
use yii\base\Model;

class CreateProductFormConvertor extends Model
{
    public function saveModelFromForm(Product $model, CreateProductForm $form)
    {
        $model->name = $form->name;
        $model->image_id = $form->image_id;
        $model->sku = $form->sku;
        $model->url_alias = Translit::convert($model->name);

        if ($model->save()) {
            if (!empty($form->image_id)) {
                $mediaItem = new ProductImage();
                $mediaItem->product_id = $model->id;
                $mediaItem->image_id = $model->image_id;
                $mediaItem->save();
            }

            foreach ($form->category_id as $category_id) {
                $category = new ProductCategory();
                $category->category_id = $category_id;
                $category->product_id = $model->id;
                $category->save();
            }
        }

        return $model;
    }
}