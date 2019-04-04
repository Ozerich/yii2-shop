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
        $category = $product->category;

        /** @var Field[] $fields */
        $fields = Field::find()->joinWith('categoryFields')
            ->andWhere('fields.category_id <> :main_id', [':main_id' => $category->id])
            ->andWhere('category_fields.category_id=:category_id', [
                ':category_id' => $category->id
            ])
            ->all();

        /** @var Field[] $fields2 */
        $fields2 = Field::find()->joinWith('categoryFields')
            ->andWhere('fields.category_id = :main_id', [':main_id' => $category->id])
            ->andWhere('category_fields.category_id=:category_id', [
                ':category_id' => $category->id
            ])
            ->all();

        $fields = array_merge($fields, $fields2);

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