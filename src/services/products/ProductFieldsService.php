<?php

namespace ozerich\shop\services\products;

use ozerich\shop\constants\FieldType;
use ozerich\shop\models\Field;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductFieldValue;
use ozerich\shop\structures\ProductField;

class ProductFieldsService
{
    /**
     * @param Product $product
     * @return ProductField[]
     */
    public function getFieldsForProduct(Product $product)
    {
        $result = [];

        $categoryFields = $product->category->categoryFields;
        foreach ($categoryFields as $categoryField) {
            $item = new ProductField();

            $item->setField($categoryField->field);
            $item->setValue(ProductFieldValue::find()->select('value')
                ->andWhere('field_id=:field_id', [':field_id' => $categoryField->field_id])
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
        $model->value = is_array($value) ? implode(';',$value) : $value;
        $model->save();
    }

    public function getFieldPlainValue(ProductFieldValue $productField)
    {
        if ($productField->field->type == FieldType::BOOLEAN) {
            $result = $productField->value ? $productField->field->yes_label : $productField->field->no_label;
        } else if ($productField->field->type == FieldType::STRING) {
            $result = $productField->value;
        } else if ($productField->field->type == FieldType::INTEGER) {
            $result = $productField->field->value_prefix . (int)$productField->value . $productField->field->value_suffix;
        } else if ($productField->field->type == FieldType::SELECT) {
            $result = $productField->field->multiple ? explode(';', $productField->value) : $productField->value;
        } else {
            $result = $productField->value;
        }

        return $result;
    }

    public function getFieldValue(Product $product, Field $field)
    {
        foreach ($product->productFieldValues as $productFieldValue) {
            if ($productFieldValue->field_id == $field->id) {
                return $productFieldValue->value;
            }
        }

        return null;
    }
}