<?php

namespace ozerich\shop\services\products;

use ozerich\shop\models\Category;
use ozerich\shop\models\CategoryCondition;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductImage;
use ozerich\shop\traits\ServicesTrait;

class ProductColorsService
{
    use ServicesTrait;

    public function getProductColorIds(Product $product)
    {
        $result = ProductImage::find()->andWhere('product_id = :product_id', [':product_id' => $product->id])
            ->andWhere('color_id is not null')
            ->select('color_id')
            ->column();;

        return array_unique($result);
    }

    public function updateCategoriesWithColorIds($colorIds = [])
    {
        $result = [];

        /** @var CategoryCondition[] $conditionals */
        $conditionals = CategoryCondition::find()->andWhere('type=:type', [':type' => 'COLOR'])->all();

        foreach ($conditionals as $conditional) {
            if (in_array($conditional->category_id, $result)) {
                continue;
            }

            $value = explode(';', $conditional->value);
            foreach ($value as $color_id) {
                if (in_array($color_id, $colorIds)) {
                    $result[] = $conditional->category_id;
                }
            }
        }

        foreach ($result as $categoryId) {
            $this->categoryProductsService()->afterConditionalCategoryChanged(Category::findOne($categoryId));
        }
    }
}