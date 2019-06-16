<?php

namespace ozerich\shop\services\categories;

use ozerich\shop\constants\CategoryType;
use ozerich\shop\models\Category;
use ozerich\shop\models\CategoryManufacture;
use ozerich\shop\models\Manufacture;
use ozerich\shop\models\Product;
use ozerich\shop\traits\ServicesTrait;

class CategoryManufacturesService
{
    use ServicesTrait;

    private function addManufactureToCategory($manufactureId, $categoryId)
    {
        CategoryManufacture::deleteAll(['category_id' => $categoryId, 'manufacture_id' => $manufactureId]);

        $model = new CategoryManufacture();
        $model->manufacture_id = $manufactureId;
        $model->category_id = $categoryId;
        $model->save();
    }

    public function onUpdateCategory($categoryId)
    {
        $category = Category::findOne($categoryId);

        CategoryManufacture::deleteAll(['category_id' => $categoryId]);

        $manufacture_ids = $this->productGetService()->getSearchByCategoryQuery($categoryId)->select('products.manufacture_id')->column();
        $manufacture_ids = array_unique($manufacture_ids);

        foreach ($manufacture_ids as $manufacture_id) {
            if ($manufacture_id) {
                $this->addManufactureToCategory($manufacture_id, $categoryId);
                if ($category->type == CategoryType::CATALOG && $category->parent_id) {
                    $this->addManufactureToCategory($manufacture_id, $category->parent_id);
                }
            }
        }
    }

    /**
     * @param Category $category
     * @return Manufacture[]
     */
    public function getCategoryManufactures(Category $category)
    {
        if ($category->type == CategoryType::CONDITIONAL) {
            $category_id = $category->parent_id;
        } else {
            $category_id = $category->id;
        }

        $ids = CategoryManufacture::find()->andWhere('category_id=:category_id', [':category_id' => $category_id])->select('manufacture_id')->column();

        if (empty($ids)) {
            return [];
        }

        return Manufacture::find()->andWhere('id IN (' . implode(',', $ids) . ')')->all();
    }

    public function setProductManufacture(Product $product, ?Manufacture $manufacture)
    {
        $newManufactureId = $manufacture ? $manufacture->id : null;

        if ($newManufactureId == $product->manufacture_id) {
            return $product;
        }

        $product->manufacture_id = $newManufactureId;
        $product->save(false, ['manufacture_id']);

        if ($product->category) {
            $this->onUpdateCategory($product->category);
        }

        return $product;
    }
}