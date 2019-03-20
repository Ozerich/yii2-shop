<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use ozerich\shop\models\Category;
use ozerich\shop\modules\admin\api\models\FieldDTO;
use ozerich\shop\modules\admin\api\models\FieldGroupDTO;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class FieldsController extends Controller
{
    public function getAllowedOrigins()
    {
        return '*';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'groups',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'index',
                    'verbs' => ['GET']
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionIndex($id)
    {
        /** @var Category $category */
        $category = Category::findOne($id);
        if (!$category) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $category->getFields()
        ]);

        return new CollectionResponse($dataProvider, FieldDTO::class);
    }

    public function actionGroups($id)
    {
        /** @var Category $category */
        $category = Category::findOne($id);
        if (!$category) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $category->getFieldGroups()
        ]);

        return new CollectionResponse($dataProvider, FieldGroupDTO::class);
    }
}