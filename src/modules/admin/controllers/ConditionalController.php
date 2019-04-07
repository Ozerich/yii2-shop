<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use ozerich\shop\constants\CategoryConditionType;
use ozerich\shop\models\Category;
use ozerich\shop\models\CategoryCondition;
use ozerich\shop\modules\admin\api\models\CategoryConditionalDTO;
use ozerich\shop\modules\admin\api\requests\conditional\ModelRequest;
use ozerich\shop\traits\ServicesTrait;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

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
            $model = new CategoryCondition();
            $model->category_id = $category->id;
            $model->compare = $condition['compare'];
            $model->type = $condition['filter'] == 'PRICE' ? CategoryConditionType::PRICE : CategoryConditionType::FIELD;
            $model->field_id = $model->type == CategoryConditionType::FIELD ? $condition['filter'] : null;
            $model->value = is_array($condition['value']) ? implode(';', $condition['value']) : $condition['value'];
            $model->save();
        }

        $this->categoryProductsService()->afterConditionalCategoryChanged($category);

        return null;
    }
}