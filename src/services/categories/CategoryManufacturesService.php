<?php

namespace ozerich\shop\services\categories;

use ozerich\shop\models\CategoryManufacture;
use ozerich\shop\traits\ServicesTrait;

class CategoryManufacturesService
{
    use ServicesTrait;

    public function onUpdateCategory($categoryId)
    {
        CategoryManufacture::deleteAll(['category_id' => $categoryId]);

        $manufacture_ids = $this->productGetService()->getSearchByCategoryQuery($categoryId)->select('products.manufacture_id')->column();
        $manufacture_ids = array_unique($manufacture_ids);

        foreach ($manufacture_ids as $manufacture_id) {
            if ($manufacture_id) {
                $model = new CategoryManufacture();
                $model->manufacture_id = $manufacture_id;
                $model->category_id = $categoryId;
                $model->save();
            }
        }
    }
}