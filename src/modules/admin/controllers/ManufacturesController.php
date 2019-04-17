<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\models\Manufacture;

class ManufacturesController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => ListAction::class,
                'query' => Manufacture::find(),
                'view' => 'index'
            ],
            'create' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Manufacture::class,
                'isCreate' => true,
                'view' => 'form',
                'redirectUrl' => '/admin/manufactures'
            ],
            'update' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Manufacture::class,
                'isCreate' => false,
                'view' => 'form',
                'redirectUrl' => '/admin/manufactures',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Manufacture::class
            ]
        ];
    }
}