<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use ozerich\api\response\ModelResponse;
use ozerich\shop\models\Category;
use ozerich\shop\models\Product;
use ozerich\shop\modules\api\models\CategoryDTO;
use ozerich\shop\modules\api\models\CategoryFullDTO;
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
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionCategories($id = null)
    {
        $root = null;
        if ($id !== null) {
            $root = Category::find()->andWhere('id=:id', [':id' => $id])->one();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $root ? Category::findByParent($root) : Category::findRoot()
        ]);

        return new CollectionResponse($dataProvider, CategoryDTO::class);
    }

    public function actionCategory($id = null, $alias = null, $subcategory = null)
    {
        /** @var Category $model */
        if ($id) {
            $model = Category::findOne($id);
        } else if ($alias) {
            if (!empty($subcategory)) {
                $parent = Category::findByAlias($alias)->one();
                if ($parent) {
                    $model = Category::findByAlias($subcategory)->andWhere('parent_id=:parent_id', [':parent_id' => $parent->id])->one();
                }
            } else {
                $model = Category::findByAlias($alias)->one();
            }
        } else {
            $model = null;
        }

        if (!$model) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        return new ModelResponse($model, CategoryFullDTO::class);
    }

    public function actionProducts($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' =>  $this->productGetService()->getSearchByCategoryQuery($id),
            'pagination' => [
                'pageSize' => 10000
            ],
        ]);

        return new CollectionResponse($dataProvider, ProductDTO::class);
    }
}