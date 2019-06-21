<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\shop\models\BlogPost;
use ozerich\shop\models\Category;
use ozerich\shop\models\Product;
use ozerich\shop\modules\api\models\BlogPostDTO;
use ozerich\shop\modules\api\models\CategoryDTO;
use ozerich\shop\modules\api\models\CategoryShortDTO;
use ozerich\shop\modules\api\models\ProductSearchDTO;
use ozerich\shop\traits\ServicesTrait;

class SearchController extends Controller
{
    use ServicesTrait;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'index',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'categories',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'posts',
                    'verbs' => 'GET'
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionIndex($query)
    {
        $items = $this->searchService()->searchProducts($query);

        return array_map(function (Product $product) {
            return (new ProductSearchDTO($product))->toJSON();
        }, $items);
    }

    public function actionCategories($query)
    {
        $items = $this->searchService()->searchCategories($query);

        return array_map(function (Category $category) {
            return (new CategoryDTO($category))->toJSON();
        }, $items);
    }

    public function actionPosts($query)
    {
        $items = $this->searchService()->searchPosts($query);

        return array_map(function (BlogPost $post) {
            return (new BlogPostDTO($post))->toJSON();
        }, $items);
    }
}