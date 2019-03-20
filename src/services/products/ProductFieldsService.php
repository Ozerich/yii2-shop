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
        $category = $product->category;

        $fields = Field::find()->andWhere('category_id=:category_id', [':category_id' => $category->id])->all();


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