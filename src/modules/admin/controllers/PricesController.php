<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\MoveAction;
use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\request\InvalidRequestException;
use ozerich\api\response\CollectionResponse;
use ozerich\api\response\ModelResponse;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductPrice;
use ozerich\shop\models\ProductPriceParam;
use ozerich\shop\models\ProductPriceParamValue;
use ozerich\shop\modules\admin\api\models\ProductPriceCommonDTO;
use ozerich\shop\modules\admin\api\models\ProductPriceDTO;
use ozerich\shop\modules\admin\api\models\ProductPriceParamDTO;
use ozerich\shop\modules\admin\api\models\ProductPriceParamValueDTO;
use ozerich\shop\modules\admin\api\requests\prices\CommonRequest;
use ozerich\shop\modules\admin\api\requests\prices\ParamItemRequest;
use ozerich\shop\modules\admin\api\requests\prices\ParamRequest;
use ozerich\shop\modules\admin\api\requests\prices\SaveRequest;
use ozerich\shop\traits\ServicesTrait;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class PricesController extends Controller
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
                    'action' => 'load',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'move-param-item',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'params',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'param',
                    'verbs' => ['POST', 'DELETE']
                ],
                [
                    'action' => 'param-items',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'param-item',
                    'verbs' => ['POST', 'DELETE']
                ],
                [
                    'action' => 'common-save',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'save',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'toggle-extended',
                    'verbs' => ['POST']
                ],
            ]
        ];

        return $behaviors;
    }

    public function actions()
    {
        return [
            'move-param-item' => [
                'class' => MoveAction::class,
                'modelClass' => ProductPriceParamValue::class,
                'conditionAttribute' => 'product_price_param_id'
            ]
        ];
    }

    /**
     * @param $id
     * @return Product
     * @throws NotFoundHttpException
     */
    private function getProduct($id)
    {
        /** @var Product $product */
        $product = Product::findOne($id);
        if (!$product) {
            throw new NotFoundHttpException('Продукта не найдено');
        }

        return $product;
    }

    public function actionLoad($id)
    {
        $product = $this->getProduct($id);

        return new ModelResponse($product, ProductPriceCommonDTO::class);
    }


    public function actionToggleExtended($id, $value)
    {
        $product = $this->getProduct($id);

        $product->is_prices_extended = $value == 1;
        $product->discount_value = null;
        $product->discount_mode = null;
        $product->price_with_discount = null;
        $product->save(false, ['is_prices_extended', 'discount_value', 'discount_mode', 'price_with_discount']);

        return null;
    }

    public function actionCommonSave($id)
    {
        $product = $this->getProduct($id);

        $request = new CommonRequest();
        $request->load();

        $product->price = $request->disabled ? null : $request->price;
        $product->price_hidden = $request->disabled;
        $product->price_hidden_text = $request->disabled ? $request->disabled_text : null;
        $product->discount_mode = $request->discount_mode;
        $product->discount_value = $request->discount_value;
        $product->stock = $request->stock;
        $product->stock_waiting_days = $request->stock_waiting_days;

        if (!$product->save(true, ['price', 'price_hidden', 'price_hidden_text', 'discount_mode', 'discount_value', 'stock', 'stock_waiting_days'])) {
            throw new InvalidRequestException(print_r($product->getErrors(), true));
        }

        $this->productPricesService()->updateProductPrice($product);
    }

    public function actionIndex($id)
    {
        /** @var Product $product */
        $product = $this->getProduct($id);

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => -1
            ],
            'query' => ProductPrice::find()->andWhere('product_id=:product_id', [':product_id' => $product->id])
        ]);

        return new CollectionResponse($dataProvider, ProductPriceDTO::class);
    }

    public function actionParams($id)
    {
        /** @var Product $product */
        $product = Product::findOne($id);
        if (!$product) {
            throw new NotFoundHttpException('Продукта не найдено');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => ProductPriceParam::find()
                ->andWhere('product_id=:product_id', [':product_id' => $product->id])
        ]);

        return new CollectionResponse($dataProvider, ProductPriceParamDTO::class);
    }

    public function actionParam($id = null)
    {
        $model = null;

        if ($id !== null) {
            /** @var ProductPriceParam $model */
            $model = ProductPriceParam::findOne($id);
            if (!$model) {
                throw new NotFoundHttpException('Параметра не найдено');
            }

            if (\Yii::$app->request->isDelete) {
                $model->delete();
                return [];
            }
        }

        $request = new ParamRequest();
        $request->load();

        /** @var Product $product */
        $product = Product::findOne($request->product_id);
        if (!$product) {
            throw new NotFoundHttpException('Продукта не найдено');
        }

        $model = $id === null ? new ProductPriceParam() : $model;

        $model->product_id = $product->id;
        $model->name = $request->name;

        if (!$model->save()) {
            throw new InvalidRequestException('Ошибка сохранения продукта');
        }

        return new ModelResponse($model, ProductPriceParamDTO::class);
    }

    public function actionParamItems($id = null)
    {
        /** @var ProductPriceParam $param */
        $param = ProductPriceParam::findOne($id);
        if (!$param) {
            throw new NotFoundHttpException('Параметра не найдено');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => ProductPriceParamValue::find()
                ->andWhere('product_price_param_id = :param_id', [':param_id' => $param->id])
        ]);

        return new CollectionResponse($dataProvider, ProductPriceParamValueDTO::class);
    }

    public function actionParamItem($id = null)
    {
        $model = null;

        if ($id !== null) {
            /** @var ProductPriceParamValue $model */
            $model = ProductPriceParamValue::findOne($id);
            if (!$model) {
                throw new NotFoundHttpException('Значения не найдено');
            }

            if (\Yii::$app->request->isDelete) {
                $model->delete();
                return [];
            }
        }

        $request = new ParamItemRequest();
        $request->load();

        /** @var ProductPriceParam $param */
        $param = ProductPriceParam::findOne($request->param_id);
        if (!$param) {
            throw new NotFoundHttpException('Параметра не найдено');
        }

        $model = $id === null ? new ProductPriceParamValue() : $model;

        $model->product_price_param_id = $param->id;
        $model->name = $request->name;
        $model->description = $request->description;

        if (!$model->save()) {
            throw new InvalidRequestException('Ошибка сохранения значения');
        }

        return new ModelResponse($model, ProductPriceParamValueDTO::class);
    }

    public function actionSave($id)
    {
        $request = new SaveRequest();
        $request->load();

        $query = ProductPrice::find()
            ->andWhere('product_id=:product_id', [':product_id' => $id])
            ->andWhere('param_value_id=:value_id', [':value_id' => $request->first_param_id]);

        if ($request->second_param_id) {
            $query->andWhere('param_value_second_id=:value2_id', [':value2_id' => $request->second_param_id]);
        }

        $model = $query->one();
        if (!$model) {
            $model = new ProductPrice();
            $model->product_id = $id;
            $model->param_value_id = $request->first_param_id;
            $model->param_value_second_id = $request->second_param_id;
        }

        if ($request->issetAttribute('value')) {
            $model->value = $request->value;
        }
        if ($request->issetAttribute('discount_mode')) {
            $model->discount_mode = $request->discount_mode;
        }
        if ($request->issetAttribute('discount_value')) {
            $model->discount_value = $request->discount_value;
        }
        if ($request->issetAttribute('stock_waiting_days')) {
            $model->stock_waiting_days = $request->stock_waiting_days;
        }
        if ($request->issetAttribute('stock')) {
            $model->stock = $request->stock;
        }

        if ($model->discount_value === null) {
            $model->discount_mode = null;
        }

        $model->save();

        $this->productPricesService()->updateProductPrice(Product::findOne($id));

        return null;
    }
}