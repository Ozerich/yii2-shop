<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Category;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductCategory;
use yii\base\Model;

class UpdateProductFormConvertor extends Model
{
    public function loadFormFromModel(Product $product)
    {
        $form = new UpdateProductForm();

        $form->name = $product->name;
        $form->image_id = $product->image_id;
        $form->schema_image_id = $product->schema_image_id;
        $form->text = $product->text;
        $form->price = $product->price;
        $form->url_alias = $product->url_alias;
        $form->is_prices_extended = $product->is_prices_extended;

        $form->category_id = array_map(function (Category $category) {
            return $category->id;
        }, $product->categories);

        return $form;
    }

    public function saveModelFromForm(Product $model, UpdateProductForm $form)
    {
        $model->name = $form->name;
        $model->image_id = $form->image_id;
        $model->schema_image_id = $form->schema_image_id;
        $model->text = $form->text;
        $model->price = $form->price;
        $model->url_alias = $form->url_alias;
        $model->is_prices_extended = $form->is_prices_extended;

        ProductCategory::deleteAll(['product_id' => $model->id]);

        foreach ($form->category_id as $cat_id) {
            $productCategoryModel = new ProductCategory();
            $productCategoryModel->category_id = $cat_id;
            $productCategoryModel->product_id = $model->id;
            $productCategoryModel->save();
        }

        return $model->save();
    }


}