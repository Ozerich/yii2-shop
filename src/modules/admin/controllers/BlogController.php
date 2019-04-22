<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\models\BlogCategory;
use ozerich\shop\models\BlogPost;
use ozerich\shop\traits\ServicesTrait;

class BlogController extends AdminController
{
    use ServicesTrait;

    public function actions()
    {
        return [
            'categories' => [
                'class' => ListAction::class,
                'models' => $this->blogService()->getTreeAsArray(),
                'pageSize' => -1,
                'view' => 'categories/index'
            ],
            'create-category' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => BlogCategory::class,
                'isCreate' => true,
                'view' => 'categories/form',
                'redirectUrl' => '/admin/blog/categories'
            ],
            'update-category' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => BlogCategory::class,
                'isCreate' => false,
                'view' => 'categories/form',
                'redirectUrl' => '/admin/blog/categories'
            ],
            'delete-category' => [
                'class' => DeleteAction::class,
                'modelClass' => BlogCategory::class
            ],

            'posts' => [
                'class' => ListAction::class,
                'query' => BlogPost::find(),
                'pageSize' => -1,
                'view' => 'posts/index'
            ],
            'create' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => BlogPost::class,
                'isCreate' => true,
                'view' => 'posts/form',
                'redirectUrl' => '/admin/blog/posts'
            ],
            'update' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => BlogPost::class,
                'isCreate' => false,
                'view' => 'posts/form',
                'redirectUrl' => '/admin/blog/posts'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => BlogPost::class
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->redirect('/admin/categories');
    }
}