<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\ArrayResponse;
use ozerich\api\response\CollectionResponse;
use ozerich\api\response\ModelResponse;
use ozerich\shop\models\Category;
use ozerich\shop\models\Field;
use ozerich\shop\models\ProductCollection;
use ozerich\shop\modules\api\models\CategoryDTO;
use ozerich\shop\modules\api\models\CategoryFullDTO;
use ozerich\shop\modules\api\models\CollectionDTO;
use ozerich\shop\modules\api\models\CollectionFullDTO;
use ozerich\shop\modules\api\models\FilterDTO;
use ozerich\shop\modules\api\models\ProductDTO;
use ozerich\shop\modules\api\models\ProductShortDTO;
use ozerich\shop\modules\api\models\ProductWithImagesDTO;
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
                    'action' => 'home',
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
                ],
                [
                    'action' => 'collections',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'collection',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'collection-products',
                    'verbs' => 'GET'
                ],
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

        /** @var Field[] $fields */
        $fields = [];
        foreach ($model->categoryFields as $categoryField) {
            if ($categoryField->field->filter_enabled) {
                $fields[] = $categoryField->field;
            }
        }

        foreach ($fields as $field) {
            $filter = new FilterDTO($field->id, $field->name . (empty($field->value_suffix) ? '' : ', ' . $field->value_suffix));
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

    public function actionHome()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->categoriesService()->getHomeCategoriesQuery()
        ]);

        return new CollectionResponse($dataProvider, CategoryDTO::class);
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
        $category = $this->categoriesService()->getCategoryById($id);
        if (!$category) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $this->productGetService()->getSearchByCategoryQuery($id)->distinct(true)
                ->joinWith('productFieldValues')
                ->joinWith('category')
                ->joinWith('image'),
            'pagination' => [
                'pageSizeLimit' => [0, 100000]
            ],
        ]);

        return new CollectionResponse(
            $dataProvider,
            $this->categoriesService()->isColorCategory($category) ? ProductWithImagesDTO::class : ProductDTO::class
        );
    }

    public function actionCollections()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->productCollectionsService()->getAllQuery()
        ]);

        return new CollectionResponse($dataProvider, CollectionDTO::class);
    }

    public function actionCollection($id = null, $alias = null)
    {
        $model = null;
        if ($id !== null) {
            $model = $this->productCollectionsService()->getById($id);
        } else {
            $model = $this->productCollectionsService()->getByAlias($alias);
        }

        if (!$model) {
            throw new NotFoundHttpException('Коллекции не найдено');
        }

        return new ModelResponse($model, CollectionFullDTO::class);
    }

    public function actionCollectionProducts($id)
    {
        /** @var ProductCollection $model */
        $model = $this->productCollectionsService()->findById($id)->joinWith('products')->one();

        if (!$model) {
            throw new NotFoundHttpException('Коллекции не найдено');
        }

        return new ArrayResponse($this->productCollectionsService()->getProducts($model), ProductShortDTO::class);
    }
}