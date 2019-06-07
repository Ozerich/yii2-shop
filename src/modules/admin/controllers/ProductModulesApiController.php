<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\MoveAction;
use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\ArrayResponse;
use ozerich\filestorage\actions\UploadAction;
use ozerich\shop\models\ProductModule;
use ozerich\shop\modules\admin\api\models\ProductModuleDTO;
use ozerich\shop\modules\admin\api\requests\modules\ModelRequest;
use ozerich\shop\traits\ServicesTrait;
use yii\web\NotFoundHttpException;

class ProductModulesApiController extends Controller
{
    use ServicesTrait;

    public function actions()
    {
        return [
            'move' => [
                'class' => MoveAction::class,
                'modelClass' => ProductModule::class,
                'conditionAttribute' => 'product_id'
            ],
            'upload' => [
                'class' => UploadAction::class,
            ],
        ];
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
                    'action' => 'upload',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'create',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'delete',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'move',
                    'verbs' => ['POST']
                ],
                [
                    'action' => 'quantity',
                    'verbs' => ['POST']
                ],
            ]
        ];

        return $behaviors;
    }

    public function actionIndex($id)
    {
        $product = $this->productModulesService()->getModuleProductById($id);
        if (!$product) {
            throw new NotFoundHttpException();
        }

        return new ArrayResponse($product->modules, ProductModuleDTO::class);
    }

    public function actionCreate($id)
    {
        $product = $this->productModulesService()->getModuleProductById($id);
        if (!$product) {
            throw new NotFoundHttpException();
        }

        $request = new ModelRequest();
        $request->load();

        $this->productModulesService()->createModule($product, $request->name, $request->sku, $request->comment, $request->price, $request->discount_mode, $request->discount_value, $request->images);
    }

    public function actionDelete($id)
    {
        $module = $this->productModulesService()->getModuleById($id);
        if (!$module) {
            throw new NotFoundHttpException();
        }

        $this->productModulesService()->deleteModule($module);
    }

    public function actionQuantity($id)
    {
        $module = $this->productModulesService()->getModuleById($id);
        if (!$module) {
            throw new NotFoundHttpException();
        }

        $this->productModulesService()->setQuantity($module, \Yii::$app->request->post('value'));
    }
}
