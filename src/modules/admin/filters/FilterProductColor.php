<?php

namespace ozerich\shop\modules\admin\filters;

use ozerich\shop\models\Category;
use yii\base\Model;
use yii\db\ActiveQuery;

class FilterProductColor extends Model
{
    public $category_id;

    public $color_id;

    public function rules()
    {
        return [
            [['category_id', 'color_id'], 'safe']
        ];
    }

    public function search(ActiveQuery $query)
    {
        if (!empty($this->category_id)) {
            if ($this->category_id === 'empty') {
                // TODO: Fetch products without category
                //$query->andWhere('category_id is null');
            } else {
                $children_ids = Category::find()
                    ->andWhere('parent_id = :parent_id', [':parent_id' => $this->category_id])
                    ->select('id')->column();

                $ids = array_merge([$this->category_id], $children_ids);

                $query->joinWith('product.productCategories')->andWhere('product_categories.category_id IN (' . implode(',', $ids) . ')');
            }
        }

        if (!empty($this->color_id)) {
            if ($this->color_id == '') {
                $query->andWhere('color_id is null');
            } else {
                $query->andWhere('color_id=:color_id', [':color_id' => $this->color_id]);
            }
        }

        return $query;
    }
}