<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\shop\models\Page;
use ozerich\shop\modules\api\models\PageDTO;
use ozerich\shop\modules\api\models\PageFullDTO;
use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use ozerich\api\response\ModelResponse;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class PagesController extends Controller
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

    public function actionIndex($id = null)
    {
        if ($id === null) {
            $dataProvider = new ActiveDataProvider([
                'query' => Page::find()
            ]);

            return new CollectionResponse($dataProvider, PageDTO::class);
        }

        /** @var Page $model */
        $model = Page::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        return new ModelResponse($model, PageFullDTO::class);
    }
}