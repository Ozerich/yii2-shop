<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\actions\MoveAction;
use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\models\Menu;
use ozerich\shop\models\MenuItem;
use ozerich\shop\traits\ServicesTrait;
use yii\web\NotFoundHttpException;

class MenuController extends AdminController
{
    use ServicesTrait;

    public function actions()
    {
        return [
            'index' => [
                'class' => ListAction::class,
                'pageSize' => 10000,
                'models' => function ($params) {
                    $id = isset($params['id']) ? (int)$params['id'] : null;

                    $menu = Menu::findOne($id);
                    if (!$menu) {
                        throw new NotFoundHttpException();
                    }

                    return $this->menuService()->getTreeAsArray($menu);
                },
                'view' => 'index'
            ],
            'move' => [
                'class' => MoveAction::class,
                'modelClass' => MenuItem::class,
                'conditionAttribute' => ['menu_id', 'parent_id']
            ],
            'create' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => MenuItem::class,
                'isCreate' => true,
                'view' => 'form',
                'redirectUrl' => function (MenuItem $model) {
                    return '/admin/menu/' . $model->menu_id;
                },
                'defaultParams' => function ($params) {
                    return [
                        'menu_id' => $params['id']
                    ];
                },
                'viewParams' => function ($params) {
                    $id = isset($params['id']) ? (int)$params['id'] : null;

                    $menu = Menu::findOne($id);
                    if (!$menu) {
                        throw new NotFoundHttpException();
                    }

                    return [
                        'menu' => $menu
                    ];
                }
            ],
            'update' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => MenuItem::class,
                'isCreate' => false,
                'view' => 'form',
                'redirectUrl' => function (MenuItem $model) {
                    return '/admin/menu/' . $model->menu_id;
                },
                'defaultParams' => function ($params) {
                    return [
                        'menu_id' => $params['id']
                    ];
                },
                'viewParams' => function ($params) {
                    $id = isset($params['id']) ? (int)$params['id'] : null;

                    $menuItem = MenuItem::findOne($id);
                    if (!$menuItem) {
                        throw new NotFoundHttpException();
                    }

                    return [
                        'menu' => $menuItem->menu
                    ];
                }
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => MenuItem::class
            ]
        ];
    }
}