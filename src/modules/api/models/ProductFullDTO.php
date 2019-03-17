<?php

namespace ozerich\shop\modules\api\models;

use ozerich\shop\models\Image;
use ozerich\shop\models\Product;
use ozerich\shop\traits\ServicesTrait;
use ozerich\api\interfaces\DTO;

class ProductFullDTO extends Product implements DTO
{
    use ServicesTrait;

    private function getParamsJSON()
    {
        $result = [];

        $params = $this->productFieldValues;

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
                    'value' =>  $this->productFieldsService()->getFieldPlainValue($param)
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

    public function toJSON()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'image' => $this->image ? $this->image->getUrl() : null,
            'video' => $this->video,
            'text' => $this->text,
            'params' => $this->getParamsJSON(),
            'images' => array_map(function (Image $image) {
                return $image->getUrl();
            }, $this->images)
        ];
    }
}