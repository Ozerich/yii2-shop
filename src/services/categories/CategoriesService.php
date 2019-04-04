<?php

namespace ozerich\shop\services\categories;

use ozerich\shop\constants\CategoryType;
use ozerich\shop\models\Category;

class CategoriesService
{
    private function getTreeRec($parent)
    {
        if ($parent == null) {
            $items = Category::findRoot()->all();
        } else {
            $items = Category::findByParent($parent)->all();
        }

        usort($items, function (Category $a, Category $b) {
            if ($a->type == CategoryType::CONDITIONAL && $b->type == CategoryType::CATALOG) {
                return 1;
            }
            if ($b->type == CategoryType::CONDITIONAL && $a->type == CategoryType::CATALOG) {
                return -1;
            }
            return 0;
        });

        $result = [];

        foreach ($items as $item) {
            $result[$item['id']] = [
                'model' => $item,
                'plain_label' => str_repeat('-', ($item->level - 1) * 5) . $item->name,
                'children' => $this->getTreeRec($item)
            ];
        }

        return $result;
    }

    public function getTree()
    {
        return $this->getTreeRec(null);
    }

    private function rec($parentItems)
    {
        $result = [];

        foreach ($parentItems as $id => $row) {
            $result[$id] = $row;
            $result = array_merge($result, $this->rec($row['children']));
        }

        return $result;
    }

    public function getTreeAsArray()
    {
        return $this->rec($this->getTree());
    }

    public function getTreeAsPlainArray()
    {
        $array = $this->rec($this->getTree());

        $result = [];
        foreach ($array as $item) {
            $result[$item['model']['id']] = $item['plain_label'];
        }

        return $result;
    }

    public function updateCategoryLevel(Category $category)
    {
        $level = 1;

        $parent = $category->parent;
        while ($parent != null) {
            $parent = $parent->parent;
            $level = $level + 1;
        }

        $category->level = $level;
        $category->save(false, ['level']);
    }
}