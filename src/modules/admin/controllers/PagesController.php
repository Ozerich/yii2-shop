<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\shop\models\Page;
use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\controllers\base\AdminController;

class PagesController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => ListAction::class,
                'query' => Page::find(),
                'view' => 'index'
            ],
            'create' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Page::class,
                'isCreate' => true,
                'view' => 'form',
                'redirectUrl' => '/admin/pages'
            ],
            'update' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Page::class,
                'isCreate' => false,
                'view' => 'form',
                'redirectUrl' => '/admin/pages',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Page::class
            ]
        ];
    }
}