<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\shop\modules\admin\forms\CategoryChangeTypeToCatalogForm;
use ozerich\shop\traits\ServicesTrait;
use yii\web\Response;

class CategoriesApiController extends Controller
{
    use ServicesTrait;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'tree',
                    'verbs' => ['GET']
                ],
            ]
        ];

        return $behaviors;
    }

    public function actionTree()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return $this->categoriesService()->getCatalogTreeAsPlainArray();
    }
}
