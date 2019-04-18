<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use ozerich\api\response\ModelResponse;
use ozerich\shop\models\Category;
use ozerich\shop\modules\api\models\CategoryDTO;
use ozerich\shop\modules\api\models\CategoryFullDTO;
use ozerich\shop\modules\api\models\FilterDTO;
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
        $model = Category::find()->andWhere('categories.id=:id', [':id' => $id])->joinWith('categoryFields')->joinWith('manufactures')->one();
        if (!$model) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        $result = [];

        $manufactures = $model->manufactures;
        if (!empty($manufactures)) {
            $filter = new FilterDTO('MANUFACTURE', 'Производитель');
            $filter->setFilterType('SELECT');
            foreach ($manufactures as $manufacture) {
                $filter->addFilterValue($manufacture->id, $manufacture->name);
            }
            $result[] = $filter;
        }

        $fields = [];
        foreach ($model->categoryFields as $categoryField) {
            if ($categoryField->field->filter_enabled) {
                $fields[] = $categoryField->field;
            }
        }

        foreach ($fields as $field) {
            $filter = new FilterDTO($field->id, $field->name);
            $filter->setFilterType($field->type);
            foreach ($field->values as $value) {
                $filter->addFilterValue(null, $value);
            }

            $result[] = $filter;
        }

        return array_map(function (FilterDTO $filter) {
            return $filter->toJSON();
        }, $result);
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
            'query' => $this->productGetService()->getSearchByCategoryQuery($id)
                ->joinWith('productFieldValues')
                ->joinWith('category')
                ->joinWith('image'),
            'pagination' => [
                'pageSize' => 10000
            ],
        ]);

        return new CollectionResponse($dataProvider, ProductDTO::class);
    }
}