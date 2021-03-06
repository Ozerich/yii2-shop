<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use ozerich\api\response\ModelResponse;
use ozerich\shop\models\Category;
use ozerich\shop\models\Field;
use ozerich\shop\modules\admin\api\models\CategoryDTO;
use ozerich\shop\modules\admin\api\models\FieldDTO;
use ozerich\shop\modules\admin\api\requests\fields\FieldRequest;
use ozerich\shop\traits\ServicesTrait;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class FieldsController extends Controller
{
    use ServicesTrait;

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
                    'action' => 'category',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'parents',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'toggle',
                    'verbs' => ['POST']
                ],
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

    public function actionToggle($id)
    {
        /** @var Category $category */
        $category = Category::findOne($id);
        if (!$category) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        $field_id = \Yii::$app->request->post('field_id');
        $value = \Yii::$app->request->post('value') ? true : false;

        /** @var Field $field */
        $field = Field::findOne($field_id);
        if (!$field) {
            throw new NotFoundHttpException('Поля не найдено');
        }

        if ($value) {
            $this->categoryFieldsService()->addFieldToCategory($field, $category);
        } else {
            $this->categoryFieldsService()->removeFieldFromCategory($field, $category);
        }

        return null;
    }

    public function actionCategory($id)
    {
        /** @var Category $category */
        $category = Category::findOne($id);
        if (!$category) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        return new ModelResponse($category, CategoryDTO::class);
    }

    public function actionParents($id)
    {
        /** @var Category $category */
        $category = Category::findOne($id);
        if (!$category) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        /** @var Field[] $items */
        $items = $this->categoryFieldsService()->getParentFieldsForCategoryQuery($category)->all();

        $activeIds = [];

        foreach ($items as $item) {
            if ($this->categoryFieldsService()->isActiveFieldForCategory($item, $category)) {
                $activeIds[] = $item->id;
            }
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'collection' => array_map(function (Field $category) {
                return (new FieldDTO($category))->toJSON();
            }, $items),
            'active' => $activeIds
        ];
    }

    public function actionIndex($id)
    {
        /** @var Category $category */
        $category = Category::findOne($id);
        if (!$category) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $this->categoryFieldsService()->getFieldsForCategoryQuery($category),
            'pagination' => [
                'pageSize' => -1
            ]
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
        $model->category_id = $category->id;

        $model->name = $request->name;
        $model->type = $request->type;
        $model->value_suffix = $request->value_suffix;
        $model->value_prefix = $request->value_prefix;
        $model->yes_label = $request->yes_label;
        $model->no_label = $request->no_label;
        $model->values = $request->values;
        $model->multiple = $request->multiple;
        $model->filter_enabled = $request->filter_enabled ? true : false;
        $model->save();

        $this->categoryFieldsService()->addFieldToCategory($model, $category);

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
        $model->value_suffix = $request->value_suffix;
        $model->value_prefix = $request->value_prefix;
        $model->values = $request->values;
        $model->yes_label = $request->yes_label;
        $model->no_label = $request->no_label;
        $model->filter_enabled = $request->filter_enabled ? true : false;
        $model->multiple = $request->multiple;

        if (!$model->save()) {
            throw new NotFoundHttpException(print_r($model->getErrors(), true));
        }

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
}