<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\ArrayResponse;
use ozerich\shop\models\Category;
use ozerich\shop\models\Field;
use ozerich\shop\models\Product;
use ozerich\shop\modules\admin\api\requests\products\ParamsRequest;
use ozerich\shop\modules\admin\api\requests\products\UpdateParamRequest;
use ozerich\shop\modules\admin\forms\CategoryChangeTypeToCatalogForm;
use ozerich\shop\modules\api\models\ProductShortDTO;
use ozerich\shop\traits\ServicesTrait;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProductsApiController extends Controller
{
    use ServicesTrait;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'product-params',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'update-param',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'search',
                    'verbs' => ['GET']
                ],
            ]
        ];

        return $behaviors;
    }

    public function actionProductParams()
    {
        $request = new ParamsRequest();
        $request->load();

        $fields = [];
        foreach ($request->fields as $field) {
            $fields[] = Field::findOne($field);
        }

        $query = Product::find()
            ->joinWith('manufacture')
            ->joinWith('productPriceParams');

        $ids = Category::find()->select('id')->andWhere('parent_id=:parent_id', [':parent_id' => $request->category_id])->column();
        $ids = $ids ? array_merge([$request->category_id], $ids) : [$request->category_id];

        $query->andWhere('category_id in (' . implode(',', $ids) . ')');

        /** @var Product[] $products */
        $products = $query->all();

        $result = [];

        foreach ($products as $product) {
            $result[] = [
                'id' => $product->id,
                'name' => $product->name,
                'manufacture' => $product->manufacture ? $product->manufacture->name : null,
                'fields' => array_map(function (Field $field) use ($product) {
                    return [
                        'id' => $field->id,
                        'value' => $this->productFieldsService()->getFieldValue($product, $field)
                    ];
                }, $fields)
            ];
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    public function actionUpdateParam()
    {
        $request = new UpdateParamRequest();
        $request->load();

        $product = Product::findOne($request->product_id);
        if (!$product) {
            throw new NotFoundHttpException();
        }

        $field = Field::findOne($request->field_id);
        if (!$field) {
            throw new NotFoundHttpException();
        }

        $this->productFieldsService()->setProductFieldValue($product, $field, $request->value);

        $this->categoryProductsService()->afterProductParamsChanged($product);

        return null;
    }

    public function actionSearch($query)
    {
        $query = trim($query);

        if (empty($query)) {
            return [];
        }

        $products = $this->searchService()->adminSearchProducts($query, 20);

        return new ArrayResponse($products, ProductShortDTO::class);
    }
}
