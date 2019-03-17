<?php

namespace ozerich\shop\modules\api\models;

use ozerich\shop\models\MenuItem;
use ozerich\api\interfaces\DTO;

class MenuItemDTO extends MenuItem implements DTO
{
    public function toJSON()
    {
        /** @var MenuItem[] $children */
        $children = MenuItem::find()->andWhere('parent_id=:parent_id', [':parent_id' => $this->id])->all();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'children' => array_map(function (MenuItem $item) {
                return (new MenuItemDTO($item))->toJSON();
            }, $children)
        ];
    }
}