<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use ozerich\api\response\ModelResponse;
use ozerich\shop\models\Category;
use ozerich\shop\models\Field;
use ozerich\shop\models\FieldGroup;
use ozerich\shop\modules\admin\api\models\FieldDTO;
use ozerich\shop\modules\admin\api\models\FieldGroupDTO;
use ozerich\shop\modules\admin\api\requests\fields\FieldRequest;
use ozerich\shop\modules\admin\api\requests\fields\GroupRequest;
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
                    'action' => 'index',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'create-field',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'save-field',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'delete-field',
                    'verbs' => ['DELETE']
                ],
                [
                    'action' => 'groups',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'create-group',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'save-group',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'delete-group',
                    'verbs' => ['DELETE']
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

    public function actionCreateField($id)
    {
        /** @var Category $category */
        $category = Category::findOne($id);
        if (!$category) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        $request = new FieldRequest();
        $request->load();

        $model = new Field();
        $model->name = $request->name;
        $model->type = $request->type;
        $model->group_id = $request->group_id;
        $model->category_id = $category->id;
        $model->save();

        return new ModelResponse($model, FieldDTO::class);
    }

    public function actionSaveField($id)
    {
        /** @var Field $model */
        $model = Field::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $request = new FieldRequest();
        $request->load();

        $model->name = $request->name;
        $model->type = $request->type;
        $model->group_id = $request->group_id;
        $model->save();

        return new ModelResponse($model, FieldDTO::class);
    }

    public function actionDeleteField($id)
    {
        /** @var Field $model */
        $model = Field::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $model->delete();

        return null;
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

    public function actionCreateGroup($id)
    {
        /** @var Category $category */
        $category = Category::findOne($id);
        if (!$category) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        $request = new GroupRequest();
        $request->load();

        $model = new FieldGroup();
        $model->name = $request->name;
        $model->category_id = $category->id;
        $model->save();

        return new ModelResponse($model, FieldGroupDTO::class);
    }

    public function actionSaveGroup($id)
    {
        /** @var FieldGroup $model */
        $model = FieldGroup::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }


        $request = new GroupRequest();
        $request->load();

        $model->name = $request->name;
        $model->save();

        return new ModelResponse($model, FieldGroupDTO::class);
    }

    public function actionDeleteGroup($id)
    {
        /** @var FieldGroup $model */
        $model = FieldGroup::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $model->delete();

        return null;
    }
}