<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use ozerich\api\response\ModelResponse;
use ozerich\shop\models\Product;
use ozerich\shop\modules\api\models\ProductDTO;
use ozerich\shop\modules\api\models\ProductFullDTO;
use ozerich\shop\modules\api\responses\products\PricesResponse;
use ozerich\shop\modules\api\responses\products\ProductSectionsResponse;
use ozerich\shop\traits\ServicesTrait;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ProductsController extends Controller
{
    use ServicesTrait;

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
                ],
                [
                    'action' => 'home',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'product-sections',
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

    public function actionHome()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()->andWhere('popular=1')
        ]);

        return new CollectionResponse($dataProvider, ProductDTO::class);
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

        if ($model->is_prices_extended == false) {
            return $response;
        }

        $response->setParams($model->productPriceParams);
        $response->setPrices($model->prices);

        return $response;
    }

    public function actionProductSections($id)
    {
        /** @var Product $product */
        $product = Product::find()
            ->andWhere('products.id=:id', [':id' => $id])
            ->joinWith('productCategories')
            ->one();

        if (!$product) {
            throw new NotFoundHttpException('Товара не найдено');
        }

        $response = new ProductSectionsResponse();

        $sameProducts = $this->productGetService()->getSameProducts($product);
        if (!empty($sameProducts)) {
            $response->add('Похожие товары', $sameProducts);
        }

        return $response;
    }
}