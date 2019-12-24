<?php

namespace ozerich\shop\modules\admin\controllers;

use app\modules\admin\search\ItemSearch;
use ozerich\admin\actions\MoveAction;
use ozerich\shop\models\Banners;
use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\models\BannerSearch;

class BannerController extends AdminController
{
    public function actions()
    {
        return [
            'create' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Banners::class,
                'isCreate' => true,
                'view' => 'form',
                'redirectUrl' => '/admin/banner'
            ],
            'update' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Banners::class,
                'isCreate' => false,
                'view' => 'form',
                'redirectUrl' => '/admin/banner',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Banners::class
            ],
            'move' => [
                'class' => MoveAction::class,
                'modelClass' => Banners::class,
                'conditionAttribute' => ['area_id']
            ],
        ];
    }

    public function actionIndex(){
        $search = new BannerSearch();
        $queryParams =\Yii::$app->request->getQueryParams();
        $area = \Yii::$app->request->get('BannerSearch');
        $area = is_array($area) && array_key_exists('area_id', $area) ? $area['area_id'] : false;
        $dataProvider = $search->search($queryParams, $area);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $search,
            'area' => $area,
            'showSort' => $area
        ]);
    }
}
