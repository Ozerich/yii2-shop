<?php

namespace ozerich\shop\services\blog;

use ozerich\shop\constants\PostStatus;
use ozerich\shop\models\BlogCategory;
use ozerich\shop\models\BlogPost;
use yii\db\ActiveQuery;

class BlogService
{
    private function getTreeRec($parent)
    {
        if ($parent == null) {
            $items = BlogCategory::findRoot()->all();
        } else {
            $items = BlogCategory::findByParent($parent)->all();
        }

        $result = [];

        foreach ($items as $item) {
            $result[$item['id']] = [
                'model' => $item,
                'plain_label' => str_repeat('-', (($item->parent_id ? 2 : 1) - 1) * 5) . $item->name,
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
            $result[$item['model']['id']] = $item['plain_label'];
        }

        return $result;
    }

    /**
     * @param BlogPost $post
     * @return ActiveQuery
     */
    public function getSamePostsQuery(BlogPost $post)
    {
        return BlogPost::findByStatus(PostStatus::PUBLISHED)->andWhere('id <> :id', [':id' => $post->id]);
    }
}