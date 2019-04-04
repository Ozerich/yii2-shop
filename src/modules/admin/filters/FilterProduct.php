<?php

namespace ozerich\shop\modules\admin\filters;

use ozerich\shop\models\Category;
use ozerich\shop\models\Product;
use yii\db\ActiveQuery;

class FilterProduct extends Product
{
    public $category_id;

    public function rules()
    {
        return [
            [['name', 'category_id'], 'safe']
        ];
    }

    public static function getCategoryFilterArray()
    {
        $items = Category::getTree();

        $result = [
            'empty' => 'Без категории'
        ];

        foreach ($items as $item) {
            $result[$item->id] = $item->parent_id ? '----- ' . $item->name : $item->name;
        }

        return $result;
    }

    public function search(ActiveQuery $query)
    {
        if (!empty($this->name)) {
            $query->andWhere('name LIKE :name', [':name' => '%' . $this->name . '%']);
        }

        if (!empty($this->category_id)) {
            if ($this->category_id === 'empty') {
                // TODO: Fetch products without category
                //$query->andWhere('category_id is null');
            } else {
                $children_ids = Category::find()
                    ->andWhere('parent_id = :parent_id', [':parent_id' => $this->category_id])
                    ->select('id')->column();

                $ids = array_merge([$this->category_id], $children_ids);

                if (!empty($ids)) {
                    $query->andWhere('category_id IN (' . implode(',', $ids) . ')');
                }
            }
        }

        return $query;
    }
}