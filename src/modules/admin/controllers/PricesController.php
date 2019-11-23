<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\MoveAction;
use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\request\InvalidRequestException;
use ozerich\api\response\CollectionResponse;
use ozerich\api\response\ModelResponse;
use ozerich\shop\constants\ProductType;
use ozerich\shop\models\Category;
use ozerich\shop\models\Currency;
use ozerich\shop\models\Manufacture;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductModule;
use ozerich\shop\models\ProductPrice;
use ozerich\shop\models\ProductPriceParam;
use ozerich\shop\models\ProductPriceParamValue;
use ozerich\shop\modules\admin\api\models\CurrencyDTO;
use ozerich\shop\modules\admin\api\models\ProductPriceCommonDTO;
use ozerich\shop\modules\admin\api\models\ProductPriceDTO;
use ozerich\shop\modules\admin\api\models\ProductPriceParamDTO;
use ozerich\shop\modules\admin\api\models\ProductPriceParamValueDTO;
use ozerich\shop\modules\admin\api\requests\prices\CommonRequest;
use ozerich\shop\modules\admin\api\requests\prices\ParamItemRequest;
use ozerich\shop\modules\admin\api\requests\prices\ParamRequest;
use ozerich\shop\modules\admin\api\requests\prices\PricesRequest;
use ozerich\shop\modules\admin\api\requests\prices\SaveModuleRequest;
use ozerich\shop\modules\admin\api\requests\prices\SaveRequest;
use ozerich\shop\modules\api\models\PriceDTO;
use ozerich\shop\traits\ServicesTrait;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
                    'action' => 'products',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'init',
                    'verbs' => ['GET']
                ],
                [
                    'action' => 'currencies',
                    'verbs' => ['GET']
                ],
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
                    'action' => 'param-move',
                    'verbs' => ['POST']
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
                    'action' => 'save-module',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'currency',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'toggle-extended',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'save-batch',
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
            ],
            'param-move' => [
                'class' => MoveAction::class,
                'modelClass' => ProductPriceParam::class,
                'conditionAttribute' => 'product_id'
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
        $product->price_note = $request->price_note;
        $product->is_price_from = $request->is_price_from;
        $product->currency_id = $request->currency ? $request->currency : Currency::defaultId();

        if (!$product->save(true, ['currency_id', 'price_note', 'is_price_from', 'price', 'price_hidden', 'price_hidden_text', 'discount_mode', 'discount_value', 'stock', 'stock_waiting_days'])) {
            throw new InvalidRequestException(print_r($product->getErrors(), true));
        }

        $this->productPricesService()->updateProductPrice($product);
    }

    public function actionCurrency($id)
    {
        $product = $this->getProduct($id);

        $currency = \Yii::$app->request->post('currency');

        $product->currency_id = $currency;
        $product->save(false, ['currency_id']);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'success' => true
        ];
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

        if (!$request->first_param_id && !$request->second_param_id) {
            $model = Product::findOne($id);
        } else {
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
        }

        if ($model instanceof ProductPrice && $request->issetAttribute('value')) {
            $model->value = $request->value;
        } elseif ($request->issetAttribute('stock')) {
            $model->stock = $request->stock;
        }

        if ($request->issetAttribute('price')) {
            if ($model instanceof ProductPrice) {
                $model->value = $request->price;
            } elseif ($model instanceof Product) {
                $model->price = $request->price;
            }
        }

        if ($request->issetAttribute('discount_mode')) {
            $model->discount_mode = $request->discount_mode;
        }
        if ($request->issetAttribute('discount_value')) {
            $model->discount_value = $request->discount_value;
            if (!$model->discount_value) {
                $model->discount_mode = null;
            }
        }
        if ($request->issetAttribute('stock_waiting_days')) {
            $model->stock_waiting_days = $request->stock_waiting_days;
        }

        if ($model instanceof ProductPrice && !$model->value) {
            $model->delete();
        } else {
            $model->save();
        }

        $this->productPricesService()->updateProductPrice(Product::findOne($id));

        return null;
    }

    public function actionSaveModule($id)
    {
        $request = new SaveModuleRequest();
        $request->load();

        $module = $this->productModulesService()->getModuleById($id);
        if (!$module) {
            throw new NotFoundHttpException();
        }

        $this->productModulesService()->updateModulePrice($module, $request->price, $request->discount_mode, $request->discount_value);
    }

    private function getProductPriceJSON($prices, $first_param_id, $second_param_id = null)
    {
        $price = null;

        foreach ($prices as $_price) {
            if ($_price->param_value_id == $first_param_id && $_price->param_value_second_id == $second_param_id) {
                $price = $_price;
                break;
            }
        }

        return $price ? (new \ozerich\shop\modules\api\models\ProductPriceDTO($price))->toJSON() : null;
    }

    public function actionProducts()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $request = new PricesRequest();
        $request->load();

        $query = Product::find()->andWhere('sale_disabled = 0')->joinWith('productPriceParams')->joinWith('prices');

        if ($request->manufacture_id) {
            $query->andWhere('manufacture_id=:manufacture_id', [':manufacture_id' => $request->manufacture_id]);
        }
        if ($request->category_id) {
            $ids = Category::find()->select('id')->andWhere('parent_id=:parent_id', [':parent_id' => $request->category_id])->column();
            $ids = $ids ? array_merge([$request->category_id], $ids) : [$request->category_id];

            $query->andWhere('category_id in (' . implode(',', $ids) . ')');
        }

        /** @var Product[] $products */
        $products = $query->all();

        $result = [];

        foreach ($products as $product) {
            $model = [
                'id' => $product->id,
                'type' => $product->type,
                'name' => $product->name,
                'price' => (new PriceDTO($product))->toJSON(),
                'currency_id' => $product->currency_id,
                'children' => []
            ];

            if ($product->is_prices_extended) {
                if (count($product->productPriceParams) == 1) {
                    $priceParam = $product->productPriceParams[0];
                    foreach ($priceParam->productPriceParamValues as $value) {
                        $model['children'][] = [
                            'params' => [
                                [
                                    'id' => $value->id,
                                    'label' => $priceParam->name,
                                    'value' => $value->name
                                ]
                            ],
                            'price' => $this->getProductPriceJSON($product->prices, $value->id)
                        ];
                    }
                } elseif (count($product->productPriceParams) == 2) {
                    $firstParam = $product->productPriceParams[0];
                    $secondParam = $product->productPriceParams[1];

                    foreach ($firstParam->productPriceParamValues as $firstParamValue) {
                        foreach ($secondParam->productPriceParamValues as $secondParamValue) {
                            $model['children'][] = [
                                'params' => [
                                    [
                                        'id' => $firstParamValue->id,
                                        'label' => $firstParam->name,
                                        'value' => $firstParamValue->name
                                    ],
                                    [
                                        'id' => $secondParamValue->id,
                                        'label' => $secondParam->name,
                                        'value' => $secondParamValue->name
                                    ]
                                ],
                                'price' => $this->getProductPriceJSON($product->prices, $firstParamValue->id, $secondParamValue->id)
                            ];
                        }
                    }
                }
            }

            if ($product->type == ProductType::MODULAR) {
                $model['modules'] = array_map(function (ProductModule $productModule) {
                    return [
                        'id' => $productModule->id,
                        'name' => $productModule->productValue ? $productModule->productValue->name : $productModule->name,
                        'sku' => $productModule->sku,
                        'is_catalog' => $productModule->product_value_id != null,
                        'price' => [
                            'price' => $productModule->price,
                            'discount_mode' => $productModule->discount_mode,
                            'discount_value' => $productModule->discount_value,
                            'price_with_discount' => $productModule->price_with_discount
                        ]
                    ];
                }, $product->modules);
            }

            $result[] = $model;
        }

        if ($request->without_price) {
            $result = array_filter($result, function ($product) {
                if ($product['price']['price'] == null) {
                    return true;
                }

                foreach ($product['children'] as $param) {
                    if (!$param['price']['price']) {
                        return true;
                    }
                }

                return false;
            });

            foreach ($result as &$row) {
                $row['children'] = array_values(array_filter($row['children'], function ($child) {
                    return $child['price']['price'] == null;
                }));
            }


            $result = array_values($result);
        }

        return $result;
    }

    public function actionInit()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'categories' => $this->categoriesService()->getCatalogTreeAsPlainArray(),
            'manufactures' => ArrayHelper::map(Manufacture::find()->all(), 'id', 'name')
        ];
    }

    public function actionCurrencies()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        /** @var Currency[] $currencies */
        $currencies = Currency::find()->all();

        return array_map(function (Currency $currency) {
            return (new CurrencyDTO($currency))->toJSON();
        }, $currencies);
    }

    public function actionSaveBatch($id)
    {
        $product = Product::findOne($id);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        ProductPrice::deleteAll([
            'product_id' => $product->id
        ]);

        $prices = \Yii::$app->request->post('prices');
        foreach ($prices as $price) {
            $model = new ProductPrice();
            $model->product_id = $product->id;
            $model->param_value_id = $price['value_id'];
            $model->param_value_second_id = $price['second_value_id'];
            $model->value = $price['value'];
            $model->discount_mode = $price['discount_mode'];
            $model->discount_value = $price['discount_value'];
            $model->stock = $price['stock'];
            $model->stock_waiting_days = $price['stock_waiting_days'];
            $model->save();
        }

        $this->productPricesService()->updateProductPrice($product);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [];
    }
}