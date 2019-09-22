<?php

namespace ozerich\shop\services\blog;

use ozerich\shop\constants\CategoryType;
use ozerich\shop\constants\PostStatus;
use ozerich\shop\models\BlogCategory;
use ozerich\shop\models\BlogPost;
use ozerich\shop\models\BlogPostsToProductCategories;
use ozerich\shop\models\Product;
use ozerich\shop\traits\ServicesTrait;
use yii\db\ActiveQuery;

class BlogService
{
    use ServicesTrait;

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
        return $post->getSamePosts()->andWhere('blog_posts.status=:status', [':status' => PostStatus::PUBLISHED]);
    }

    public function getProductPosts(Product $product, $limit = 4)
    {
        $all = BlogPostsToProductCategories::find()->joinWith('post')->andWhere('blog_posts.status=:status', [':status' => PostStatus::PUBLISHED])->all();

        $map = [];
        foreach ($all as $item) {
            if (!isset($map[$item->category_id])) {
                $map[$item->category_id] = [];
            }

            $map[$item->category_id][] = $item->post_id;
        }

        $categories = $product->getProductCategories()->joinWith('category')->all();

        $category_ids = [];
        foreach ($categories as $category) {
            $category_ids[] = [
                'id' => $category->category_id,
                'type' => $category->category->type
            ];
        }

        usort($category_ids, function ($a, $b) {
            if ($a['type'] == $b['type']) {
                return 0;
            }
            return $a['type'] == CategoryType::CATALOG ? 1 : -1;
        });

        $result = [];
        foreach ($category_ids as $category_id) {
            $category_id = $category_id['id'];
            if (!isset($map[$category_id])) {
                continue;
            }

            foreach ($map[$category_id] as $post_id) {
                if (!in_array($post_id, $result)) {
                    $result[] = $post_id;
                    if (count($result) >= $limit) {
                        break;
                    }
                }
            }
            if (count($result) >= $limit) {
                break;
            }
        }

        if (empty($result)) {
            return [];
        }

        return BlogPost::findByStatus(PostStatus::PUBLISHED)->andWhere('id IN (' . implode(',', $result) . ')')->all();
    }
}