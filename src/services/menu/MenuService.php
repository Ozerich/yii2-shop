<?php

namespace ozerich\shop\services\menu;

use ozerich\shop\models\Menu;
use ozerich\shop\models\MenuItem;

class MenuService
{
    private function getTreeRec(Menu $menu, $parent)
    {
        if ($parent == null) {
            $items = MenuItem::findRoot($menu)->all();
        } else {
            $items = MenuItem::findByParent($menu, $parent)->all();
        }

        $result = [];

        foreach ($items as $item) {
            $result[$item['id']] = [
                'model' => $item,
                'plain_label' => str_repeat('-', (($item->parent_id ? 2 : 1) - 1) * 5) . $item->title,
                'children' => array_values($this->getTreeRec($menu, $item))
            ];
        }

        return $result;
    }

    public function getTree(Menu $menu)
    {
        return $this->getTreeRec($menu, null);
    }

    private function rec(Menu $menu, $parentItems)
    {
        $result = [];

        foreach ($parentItems as $id => $row) {
            $result[] = $row;
            $result = array_merge($result, $this->rec($menu, $row['children']));
        }

        return $result;
    }

    public function getTreeAsArray(Menu $menu)
    {
        return $this->rec($menu, $this->getTree($menu));
    }
}