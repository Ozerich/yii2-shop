<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\models\ProductCollection;

class CollectionsController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => ListAction::class,
                'query' => ProductCollection::find(),
                'view' => 'index',
                'pageSize' => 500
            ],
            'create' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => ProductCollection::class,
                'isCreate' => true,
                'view' => 'form',
                'redirectUrl' => '/admin/collections'
            ],
            'update' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => ProductCollection::class,
                'isCreate' => false,
                'view' => 'form',
                'redirectUrl' => '/admin/collections',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => ProductCollection::class
            ]
        ];
    }
}