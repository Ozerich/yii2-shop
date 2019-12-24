<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\response\ArrayResponse;
use ozerich\shop\models\BannerAreas;
use ozerich\shop\models\Banners;
use ozerich\shop\modules\api\models\BannerAreaDTO;
use ozerich\shop\modules\api\models\BannerDTO;
use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use yii\web\NotFoundHttpException;

class BannersController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'index',
                    'verbs' => 'GET'
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionIndex($alias)
    {
        $area = BannerAreas::findOne([
            'alias' => $alias
        ]);
        if(!$area) {
            throw new NotFoundHttpException('Алиас не найден');
        }
        $models = Banners::find()->where([
            'area_id' => $area->id
        ])->orderBy(['priority' => SORT_ASC, ])->all();
        return new ArrayResponse($models, BannerDTO::class);
    }
}
