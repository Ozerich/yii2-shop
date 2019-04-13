<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use ozerich\api\response\ModelResponse;
use ozerich\shop\models\Category;
use ozerich\shop\models\Field;
use ozerich\shop\modules\api\models\CategoryDTO;
use ozerich\shop\modules\api\models\CategoryFullDTO;
use ozerich\shop\modules\api\models\FilterFieldDTO;
use ozerich\shop\modules\api\models\ProductDTO;
use ozerich\shop\traits\ServicesTrait;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class CatalogController extends Controller
{
    use ServicesTrait;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'categories',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'category',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'products',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'filters',
                    'verbs' => 'GET'
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionFilters($id)
    {
        $model = Category::find()->andWhere('categories.id=:id', [':id' => $id])->joinWith('categoryFields')->one();
        if (!$model) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        $fields = [];
        foreach ($model->categoryFields as $categoryField) {
            if ($categoryField->field->filter_enabled) {
                $fields[] = $categoryField->field;
            }
        }

        return array_map(function (Field $field) {
            return (new FilterFieldDTO($field))->toJSON();
        }, $fields);
    }

    public function actionCategories($id = null)
    {
        /** @var Category $root */
        $root = null;
        if ($id !== null) {
            $root = Category::find()->andWhere('id=:id', [':id' => $id])->one();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $root ? $this->categoriesService()->getDisplayedCategoriesForCategoryQuery($root) : Category::findRoot()
        ]);

        return new CollectionResponse($dataProvider, CategoryDTO::class);
    }

    public function actionCategory($id = null, $alias = null)
    {
        /** @var Category $model */
        $model = null;

        if ($id) {
            $model = Category::findOne($id);
        } else if ($alias) {
            $parts = explode('/', $alias);

            $parent = null;
            foreach ($parts as $part) {
                $model = Category::findByParent($parent)
                    ->andWhere('url_alias=:url_alias', [':url_alias' => $part])
                    ->one();
                $parent = $model;
            }
        }

        if (!$model) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        return new ModelResponse($model, CategoryFullDTO::class);
    }

    public function actionProducts($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->productGetService()->getSearchByCategoryQuery($id)->joinWith('productFieldValues')->joinWith('category'),
            'pagination' => [
                'pageSize' => 10000
            ],
        ]);

        return new CollectionResponse($dataProvider, ProductDTO::class);
    }
}