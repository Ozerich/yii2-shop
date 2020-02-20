<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Category;
use ozerich\shop\models\Manufacture;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductSame;
use ozerich\shop\traits\ServicesTrait;

class ProductConnectionsFormConvertor
{
    use ServicesTrait;

    public function loadFormFromModel(Product $product)
    {
        $form = new ProductConnectionsForm();

        $form->collection_id = $product->collection_id;
        $form->manufacture_id = $product->manufacture_id;
        $form->category_id = $product->category_id;
        $form->same = $product->sameProducts;

        return $form;
    }

    public function saveModelFromForm(Product $product, ProductConnectionsForm $form)
    {
        $form->two_side = explode(',', $form->two_side);
        $form->priority = explode(',', $form->priority);

        $product->collection_id = $form->collection_id;
        $product->save(false, ['collection_id', 'manufacture_id']);

        $this->categoryProductsService()->setProductCategory($product, Category::findOne($form->category_id));
        $this->categoryManufacturesService()->setProductManufacture($product, Manufacture::findOne($form->manufacture_id));

        ProductSame::deleteAll('product_id=:product_id', [':product_id' => $product->id]);

        if ($form->same) {
            foreach ($form->same as $productId) {
                $sameModel = new ProductSame();
                $sameModel->product_id = $product->id;
                $sameModel->product_same_id = $productId;
                $sameModel->priority = array_search($productId, $form->priority) !== false ? ((int)array_search($productId, $form->priority) + 1) :  count($form->priority) + 1;
                $sameModel->save();

                $productSame = ProductSame::findOne([
                    'product_id' => $productId,
                    'product_same_id' => $product->id
                ]);
                if((array_search($productId, $form->two_side) !== false) && !$productSame) {
                    $sameModel = new ProductSame();
                    $sameModel->product_id = $productId;
                    $sameModel->product_same_id = $product->id;
                    $sameModel->save();
                } elseif((array_search($productId, $form->two_side) === false) && $productSame){
                    $productSame->delete();
                }
            }
        }

        return $product;
    }
}
