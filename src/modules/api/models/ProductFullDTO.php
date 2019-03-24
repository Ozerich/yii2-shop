<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\models\Category;
use ozerich\shop\models\Image;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductCategory;
use ozerich\shop\models\ProductFieldValue;
use ozerich\shop\traits\ServicesTrait;

class ProductFullDTO extends Product implements DTO
{
    use ServicesTrait;

    private function getParamsJSON()
    {
        $productCategoryIds = array_map(function (ProductCategory $model) {
            return $model->category_id;
        }, $this->productCategories);

        $params = array_filter($this->productFieldValues, function (ProductFieldValue $value) use ($productCategoryIds) {
            return in_array($value->field->category_id, $productCategoryIds);
        });

        $groups = [];
        $no_groups = [];

        foreach ($params as $param) {
            if ($param->field->group_id !== null) {
                if (!isset($groups[$param->field->group_id])) {
                    $groups[$param->field->group_id] = [
                        'group' => [
                            'name' => $param->field->group->name,
                            'image' => $param->field->group->image ? $param->field->group->image->getUrl() : null,
                        ],
                        'fields' => []
                    ];
                }
                $groups[$param->field->group_id]['fields'][] = [
                    'label' => $param->field->name,
                    'image' => $param->field->image ? $param->field->image->getUrl() : null,
                    'value' => $this->productFieldsService()->getFieldPlainValue($param)
                ];
            } else {
                $no_groups[] = $param;
            }
        }

        $result = [];

        $groups = array_values($groups);
        foreach ($groups as $group) {
            $result[] = [
                'type' => 'GROUP',
                'model' => $group['group'],
                'fields' => $group['fields']
            ];
        }

        foreach ($no_groups as $item) {
            $result[] = [
                'type' => 'FIELD',
                'model' => [
                    'label' => $item->field->name,
                    'image' => $item->field->image ? $item->field->image->getUrl() : null
                ],
                'value' => $this->productFieldsService()->getFieldPlainValue($item)
            ];
        }

        return $result;
    }

    public function getPath()
    {
        $categories = $this->categories;

        $max_level = null;
        $max = null;

        foreach ($categories as $category) {
            if ($max_level === null || $category->level > $max_level) {
                $max_level = $category->level;
                $max = $category;
            }
        }

        $parents = [$max];
        $parent = $max->parent;

        while ($parent) {
            $parents[] = $parent;
            $parent = $parent->parent;
        }

        return array_reverse(array_map(function (Category $category) {
            return [
                'id' => $category->id,
                'url' => $category->getUrl(),
                'name' => $category->name
            ];
        }, $parents));
    }

    public function toJSON()
    {
        return [
            'id' => $this->id,
            'url_alias' => $this->url_alias,
            'name' => $this->name,
            'price' => $this->price,
            'is_prices_extended' => $this->is_prices_extended,
            'image' => $this->image ? $this->image->getUrl() : null,
            'schema' => $this->schemaImage ? $this->schemaImage->getUrl() : null,
            'video' => $this->video,
            'text' => $this->text,
            'params' => $this->getParamsJSON(),

            'path' => $this->getPath(),

            'images' => array_map(function (Image $image) {
                return [
                    'small' => $image->getUrl('gallery-preview'),
                    'big' => $image->getUrl()
                ];
            }, $this->images),

            'h1_value' => empty($this->h1_value) ? $this->name : $this->h1_value,
            'seo_title' => empty($this->seo_title) ? $this->name : $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_image' => $this->image ? $this->image->getUrl() : null,
        ];
    }
}