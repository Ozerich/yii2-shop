<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\shop\models\Menu;
use ozerich\shop\models\MenuItem;
use ozerich\shop\modules\api\models\MenuItemDTO;
use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class MiscController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'menu',
                    'verbs' => 'GET'
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionMenu($id)
    {
        $menu = Menu::findOne($id);
        if (!$menu) {
            throw new NotFoundHttpException('Меню не найдено');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => MenuItem::findRoot($menu)
        ]);

        return new CollectionResponse($dataProvider, MenuItemDTO::class);
    }
}