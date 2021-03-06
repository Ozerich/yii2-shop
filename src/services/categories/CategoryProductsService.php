<?php

namespace ozerich\shop\services\categories;

use ozerich\shop\constants\CategoryConditionType;
use ozerich\shop\constants\CategoryType;
use ozerich\shop\constants\FieldType;
use ozerich\shop\models\Category;
use ozerich\shop\models\CategoryCondition;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductCategory;
use ozerich\shop\models\ProductImage;
use ozerich\shop\traits\ServicesTrait;

class CategoryProductsService
{
    use ServicesTrait;

    private function checkNumberCondition($productValue, $conditionValue, $compare)
    {
        $productValue = (int)$productValue;
        $conditionValue = (int)$conditionValue;

        switch ($compare) {
            case 'LESS':
                return $productValue < $conditionValue;
            case 'MORE':
                return $productValue > $conditionValue;
            case 'EQUAL':
                return $productValue == $conditionValue;
            case 'NOT_EQUAL':
                return $productValue != $conditionValue;
            default:
                return false;
        }
    }

    private function checkBooleanCondition($productValue, $conditionValue, $compare)
    {
        $productValue = (boolean)$productValue;
        $conditionValue = (boolean)$conditionValue;

        switch ($compare) {
            case 'EQUAL':
                return $productValue == $conditionValue;
            case 'NOT_EQUAL':
                return $productValue != $conditionValue;
            default:
                return false;
        }
    }

    private function checkSelectCondition($productValue, $conditionValue, $compare)
    {
        $productValue = is_string($productValue) ? explode(';', $productValue) : [$productValue];

        switch ($compare) {
            case 'EQUAL':
                return in_array($conditionValue, $productValue);
            case 'NOT_EQUAL':
                return !in_array($conditionValue, $productValue);
            case 'ONE':
                $conditionValues = is_string($conditionValue) ? explode(';', $conditionValue) : [$conditionValue];
                foreach ($conditionValues as $value) {
                    if (in_array($value, $productValue)) {
                        return true;
                    }
                }
                return false;
            default:
                return false;
        }
    }

    private function checkColorCondition(Product $product, $value)
    {
        $value = is_string($value) ? explode(';', $value) : [$value];

        $colorIds = ProductImage::find()->andWhere('product_id=:product_id', [':product_id' => $product->id])
            ->andWhere('color_id is not null')
            ->select('color_id')->column();

        foreach ($colorIds as $colorId) {
            if (in_array($colorId, $value)) {
                return true;
            }
        }

        return false;
    }

    private function checkCondition(Product $product, CategoryCondition $condition)
    {
        if ($condition->type == CategoryConditionType::PRICE) {
            if ($product->price == 0) {
                return false;
            }
            return $this->checkNumberCondition($product->price, $condition->value, $condition->compare);
        } else if ($condition->type == CategoryConditionType::MANUFACTURE) {
            return $this->checkSelectCondition($product->manufacture_id, $condition->value, $condition->compare);
        } else if ($condition->type == CategoryConditionType::CATEGORY) {
            return $this->checkSelectCondition($product->category_id, $condition->value, $condition->compare);
        } else if ($condition->type == CategoryConditionType::COLOR) {
            return $this->checkColorCondition($product, $condition->value);
        } else if ($condition->type == CategoryConditionType::DISCOUNT) {
            return $product->discount_mode != null;
        }

        $field = $condition->field;
        $value = null;
        foreach ($product->productFieldValues as $productFieldValue) {
            if ($productFieldValue->field_id == $field->id) {
                $value = $productFieldValue->value;
            }
        }

        if ($field->type == FieldType::INTEGER) {
            $value = (int)$value;
        } else if ($field->type == FieldType::BOOLEAN) {
            $value = $value ? true : false;
        }

        if ($field->type == FieldType::INTEGER) {
            return $this->checkNumberCondition($value, $condition->value, $condition->compare);
        }

        if ($field->type == FieldType::BOOLEAN) {
            return $this->checkBooleanCondition($value, $condition->value, $condition->compare);
        }

        if ($field->type == FieldType::SELECT) {
            return $this->checkSelectCondition($value, $condition->value, $condition->compare);
        }

        return false;
    }

    private function checkProduct(Product $product, Category $category)
    {
        $conditions = $category->conditions;

        foreach ($conditions as $condition) {
            if (!$this->checkCondition($product, $condition)) {
                return false;
            }
        }

        return true;
    }

    private function getRootCategory(Category $category)
    {
        if (!$category->parent) {
            return $category;
        }

        $parent = $category->parent;

        while ($parent) {
            if ($parent->parent && $parent->parent->type == CategoryType::CATALOG) {
                $parent = $parent->parent;
            } else {
                break;
            }
        }

        return $parent;
    }

    public function afterProductParamsChanged(Product $product)
    {
        $root = $this->getRootCategory($product->category);

        /** @var Category[] $categories */
        $categories = Category::findByParent($root)->andWhere('type=:type', [':type' => CategoryType::CONDITIONAL])->all();

        ProductCategory::deleteAll('product_id=:product_id and category_id <> :category_id', [
            ':product_id' => $product->id,
            ':category_id' => $product->category_id
        ]);

        foreach ($categories as $category) {
            if ($this->checkProduct($product, $category)) {
                $model = new ProductCategory();
                $model->category_id = $category->id;
                $model->product_id = $product->id;
                $model->save();
            }

            $this->categoryManufacturesService()->onUpdateCategory($category->id);

            $this->updateCategoryStats($category);
        }
    }

    public function afterConditionalCategoryChanged(Category $category)
    {
        if ($category->type != CategoryType::CONDITIONAL) {
            return;
        }

        $root = $this->getRootCategory($category);
        $child_ids = Category::findByParent($root)->andWhere('type=:type', [':type' => CategoryType::CATALOG])->select('id')->column();
        $child_ids[] = $root->id;

        /** @var Product[] $products */
        $products = Product::find()
            ->joinWith('productFieldValues')
            ->andWhere('category_id IN (' . implode(',', $child_ids) . ')')
            ->all();

        $result = [];

        foreach ($products as $product) {
            if ($this->checkProduct($product, $category)) {
                $result[] = $product->id;
            }
        }

        ProductCategory::deleteAll(['category_id' => $category->id]);

        foreach ($result as $id) {
            $item = new ProductCategory();
            $item->category_id = $category->id;
            $item->product_id = $id;
            $item->save();
        }

        $this->categoryManufacturesService()->onUpdateCategory($category->id);

        $this->updateCategoryStats($category);
    }

    public function updateCategoryStats(Category $category)
    {
        $products = $this->productGetService()->getAllVisibleProductsInCategory($category);

        $min_price = null;
        $max_price = null;

        foreach ($products as $product) {
            $p = $product->price_with_discount ? $product->price_with_discount : $product->price;
            if (!$p) {
                continue;
            }

            if ($min_price === null || $min_price > $p) {
                $min_price = $p;
            }
            if ($max_price === null || $max_price < $p) {
                $max_price = $p;
            }
        }

        $category->max_price = $max_price;
        $category->min_price = $min_price;
        $category->products_count = count($products);

        $category->save(false, ['max_price', 'min_price', 'products_count']);
    }

    public function setProductCategory(Product $product, Category $category)
    {
        if (!$product->category_id || $product->category_id != $category->id) {

            if ($product->category_id) {
                ProductCategory::deleteAll(['product_id' => $product->id, 'category_id' => $product->category_id]);
            }

            $item = new ProductCategory();
            $item->product_id = $product->id;
            $item->category_id = $category->id;
            $item->save();

            $this->categoryManufacturesService()->onUpdateCategory($product->category_id);
            $this->categoryManufacturesService()->onUpdateCategory($category->id);

            if ($product->category_id) {
                $this->categoryProductsService()->updateCategoryStats(Category::findOne($product->category_id));
            }

            $this->categoryProductsService()->updateCategoryStats(Category::findOne($category->id));

            $product->category_id = $category->id;
            $product->save(false, ['category_id']);
        }

        return $product;
    }
}