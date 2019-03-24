<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\ModelResponse;
use ozerich\shop\models\Product;
use ozerich\shop\modules\api\models\ProductFullDTO;
use ozerich\shop\modules\api\responses\products\PricesResponse;
use yii\web\NotFoundHttpException;

class ProductsController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'index',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'prices',
                    'verbs' => 'GET'
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionIndex($id)
    {
        /** @var Product $product */
        $product = Product::find()
            ->andWhere('products.id=:id', [':id' => $id])
            ->joinWith('productCategories')
            ->joinWith('productFieldValues')
            ->joinWith('images')
            ->addOrderBy('popular_weight DESC')
            ->addOrderBy('name ASC')
            ->one();

        if (!$product) {
            throw new NotFoundHttpException('Продукта не найдено');
        }

        return new ModelResponse($product, ProductFullDTO::class);
    }

    public function actionPrices($id)
    {
        /** @var Product $model */
        $model = Product::find()
            ->andWhere('products.id=:id', [':id' => $id])
            ->joinWith('productPriceParams')
            ->joinWith('productPriceParams.productPriceParamValues')
            ->joinWith('prices')
            ->one();

        if (!$model) {
            throw new NotFoundHttpException('Товара не найдено');
        }

        $response = new PricesResponse();
        $response->setParams($model->productPriceParams);
        $response->setPrices($model->prices);

        return $response;
    }
}