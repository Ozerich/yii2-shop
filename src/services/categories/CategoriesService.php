<?php

namespace ozerich\shop\services\categories;

use ozerich\shop\constants\CategoryType;
use ozerich\shop\models\Category;
use yii\db\ActiveQuery;

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
                'children' => array_values($this->getTreeRec($item))
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
            $result[] = $row;
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
            $result[] = [
                'id' => $item['model']['id'],
                'label' => $item['plain_label']
            ];
        }

        return $result;
    }

    public function getCatalogTreeAsPlainArray()
    {
        $array = $this->rec($this->getTree());

        $result = [];

        foreach ($array as $item) {
            if ($item['model']->type == CategoryType::CONDITIONAL) {
                continue;
            }
            $result[] = [
                'id' => $item['model']['id'],
                'label' => $item['plain_label']
            ];
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

    /**
     * @param Category $category
     * @return array
     */
    public function getParentIds(Category $category)
    {
        $result = [];

        $parent = $category->parent;
        while ($parent) {
            $result[] = $parent->id;
            $parent = $parent->parent;
        }

        return $result;
    }

    public function getCategoriesForSameRoot(Category $category)
    {
        $model = $category;
        while ($model) {
            if (!$model->parent) {
                break;
            }
            $model = $model->parent;
        }

        return Category::findByParent($model)->all();
    }

    /**
     * @param Category $category
     * @return Category[]
     */
    public function getCatalogCategoriesForConditionalCategory(Category $category)
    {
        if ($category->type != CategoryType::CONDITIONAL) {
            return [];
        }

        $parent = $category->parent;
        while ($parent && $parent->type != CategoryType::CATALOG) {
            $parent = $parent->parent;
        }

        if (!$parent || $parent->type != CategoryType::CATALOG) {
            return [];
        }

        return Category::findByParent($parent)->andWhere('type=:type', [':type' => CategoryType::CATALOG])->all();
    }

    /**
     * @param Category $category
     * @return ActiveQuery
     */
    public function getDisplayedCategoriesForCategoryQuery(Category $category)
    {
        return Category::find()
            ->joinWith('categoryDisplayCategories')
            ->andWhere('category_display.parent_id=:parent_id', [':parent_id' => $category->id]);
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeCategoriesQuery()
    {
        return Category::find()->andWhere('home_display = 1')->addOrderBy('home_position ASC');
    }
}