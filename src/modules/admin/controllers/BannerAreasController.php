<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\shop\models\BannerAreas;
use ozerich\shop\models\Banners;
use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\controllers\base\AdminController;

class BannerAreasController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => ListAction::class,
                'query' => BannerAreas::find(),
                'view' => 'index'
            ],
            'create' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => BannerAreas::class,
                'isCreate' => true,
                'view' => 'form',
                'redirectUrl' => '/admin/banner-areas'
            ],
            'update' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => BannerAreas::class,
                'isCreate' => false,
                'view' => 'form',
                'redirectUrl' => '/admin/banner-areas',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => BannerAreas::class
            ]
        ];
    }
}
