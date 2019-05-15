<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use ozerich\shop\constants\CategoryConditionType;
use ozerich\shop\models\Category;
use ozerich\shop\models\CategoryCondition;
use ozerich\shop\models\Manufacture;
use ozerich\shop\modules\admin\api\models\CategoryConditionalDTO;
use ozerich\shop\modules\admin\api\requests\conditional\ModelRequest;
use ozerich\shop\traits\ServicesTrait;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ConditionalController extends Controller
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
                    'action' => 'index',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'categories',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'manufactures',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'save',
                    'verbs' => ['POST']
                ]
            ]
        ];

        return $behaviors;
    }

    /**
     * @param $id
     * @return Category|null
     * @throws NotFoundHttpException
     */
    private function getCategory($id)
    {
        $model = Category::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        return $model;
    }

    public function actionCategories($id)
    {
        $model = $this->getCategory($id);

        $categories = $this->categoriesService()->getCatalogCategoriesForConditionalCategory($model);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return array_map(function (Category $category) {
            return [
                'id' => $category->id,
                'name' => $category->name
            ];
        }, $categories);
    }

    public function actionManufactures($id)
    {
        $model = $this->getCategory($id);

        $manufactures = $this->categoryManufacturesService()->getCategoryManufactures($model);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return array_map(function (Manufacture $manufacture) {
            return [
                'id' => $manufacture->id,
                'name' => $manufacture->name
            ];
        }, $manufactures);
    }

    public function actionIndex($id)
    {
        $model = $this->getCategory($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getConditions()
        ]);

        return new CollectionResponse($dataProvider, CategoryConditionalDTO::class);
    }

    public function actionSave($id)
    {
        $category = $this->getCategory($id);

        CategoryCondition::deleteAll(['category_id' => $id]);

        $conditions = \Yii::$app->request->post('conditions');

        foreach ($conditions as $condition) {

            $type = CategoryConditionType::FIELD;
            if ($condition['filter'] == 'PRICE') {
                $type = CategoryConditionType::PRICE;
            } elseif ($condition['filter'] == 'CATEGORY') {
                $type = CategoryConditionType::CATEGORY;
            } elseif ($condition['filter'] == 'MANUFACTURE') {
                $type = CategoryConditionType::MANUFACTURE;
            }

            $model = new CategoryCondition();
            $model->category_id = $category->id;
            $model->compare = $condition['compare'];
            $model->type = $type;
            $model->field_id = $model->type == CategoryConditionType::FIELD ? $condition['filter'] : null;
            $model->value = is_array($condition['value']) ? implode(';', $condition['value']) : $condition['value'];
            $model->save();
        }

        $this->categoryProductsService()->afterConditionalCategoryChanged($category);

        return null;
    }
}