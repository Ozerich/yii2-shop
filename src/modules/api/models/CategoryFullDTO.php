<?php

namespace ozerich\shop\modules\api\models;

use ozerich\api\interfaces\DTO;
use ozerich\shop\constants\CategoryType;
use ozerich\shop\models\Category;
use ozerich\shop\models\CategoryCondition;
use ozerich\shop\models\CategoryDisplay;

class CategoryFullDTO extends Category implements DTO
{
    private function getColor()
    {
        if ($this->type != CategoryType::CONDITIONAL) {
            return null;
        }

        /** @var CategoryCondition $conditional */
        $conditional = CategoryCondition::find()
            ->andWhere('category_id=:category_id', [':category_id' => $this->id])
            ->andWhere('type=:type', [':type' => 'COLOR'])
            ->one();

        if (!$conditional) {
            return null;
        }

        return $conditional->value ? array_map('intval', explode(';', $conditional->value)) : null;
    }

    public function toJSON()
    {
        $parents = [];
        $parent = $this->parent;

        while ($parent) {
            $parents[] = $parent;
            $parent = $parent->parent;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'url_alias' => $this->url_alias,
            'image' => $this->image ? $this->image->getUrl() : null,
            'text' => $this->text,

            'children' => array_map(function (Category $category) {
                return (new CategoryDTO($category))->toJSON();
            }, $this->categories),

            'display_categories' => array_map(function (CategoryDisplay $category) {
                return (new CategoryDTO($category->category))->toJSON();
            }, $this->displayedCategories),

            'parents' => array_reverse(array_map(function (Category $parent) {
                return [
                    'id' => $parent->id,
                    'url' => $parent->getUrl(),
                    'name' => $parent->name
                ];
            }, $parents)),

            'h1_value' => empty($this->h1_value) ? $this->name : $this->h1_value,
            'seo_title' => empty($this->seo_title) ? $this->name : $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_image' => $this->image ? $this->image->getUrl() : null,

            'colors' => $this->getColor()
        ];
    }
}