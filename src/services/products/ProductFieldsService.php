<?php

namespace ozerich\shop\services\products;

use ozerich\shop\constants\FieldType;
use ozerich\shop\models\Category;
use ozerich\shop\models\Field;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductFieldValue;
use ozerich\shop\structures\ProductField;

class ProductFieldsService
{
    /**
     * @param Product $product
     * @param Category|null $category
     * @return ProductField[]
     */
    public function getFieldsForProduct(Product $product, ?Category $category = null)
    {
        $category_ids = $category === null ? array_map(function (Category $category) {
            return $category->id;
        }, $product->categories) : [$category->id];

        if(empty($category_ids)){
            return [];
        }

        $fields = Field::find()->andWhere('category_id IN (' . implode(',', $category_ids) . ')')->all();

        $result = [];

        foreach ($fields as $field) {
            $item = new ProductField();
            $item->setField($field);
            $item->setValue(ProductFieldValue::find()->select('value')
                ->andWhere('field_id=:field_id', [':field_id' => $field->id])
                ->andWhere('product_id=:product_id', [':product_id' => $product->id])
                ->scalar());

            $result[] = $item;
        }

        return $result;
    }

    public function setProductFieldValue(Product $product, Field $field, $value)
    {
        ProductFieldValue::deleteAll([
            'product_id' => $product->id,
            'field_id' => $field->id
        ]);

        if ($value == null) {
            return;
        }

        $model = new ProductFieldValue();
        $model->product_id = $product->id;
        $model->field_id = $field->id;
        $model->value = $value;
        $model->save();
    }

    public function getFieldPlainValue(ProductFieldValue $productField)
    {
        if ($productField->field->type == FieldType::BOOLEAN) {
            $result = $productField->value ? 'Да' : 'Нет';
        } else if ($productField->field->type == FieldType::INTEGER) {
            $result = (int)$productField->value;
        } else {
            $result = $productField->value;
        }

        return $productField->field->value_prefix . $result . $productField->field->value_suffix;
    }
}