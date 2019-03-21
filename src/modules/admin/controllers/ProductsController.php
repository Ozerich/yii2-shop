<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\constants\FieldType;
use ozerich\shop\models\Product;
use ozerich\shop\modules\admin\filters\FilterProduct;
use ozerich\shop\modules\admin\forms\CreateProductForm;
use ozerich\shop\modules\admin\forms\CreateProductFormConvertor;
use ozerich\shop\modules\admin\forms\ProductMediaForm;
use ozerich\shop\modules\admin\forms\ProductMediaFormConvertor;
use ozerich\shop\modules\admin\forms\ProductSeoForm;
use ozerich\shop\modules\admin\forms\ProductSeoFormConvertor;
use ozerich\shop\modules\admin\forms\UpdateProductForm;
use ozerich\shop\modules\admin\forms\UpdateProductFormConvertor;
use ozerich\shop\traits\ServicesTrait;
use yii\web\NotFoundHttpException;

class ProductsController extends AdminController
{
    use ServicesTrait;

    public $enableCsrfValidation = false;

    public function actions()
    {
        return [
            'index' => [
                'class' => ListAction::class,
                'query' => Product::find(),
                'view' => 'index',
                'pageSize' => 10000,
                'filterModel' => new FilterProduct(),
            ],
            'create' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Product::class,
                'formConvertor' => CreateProductFormConvertor::class,
                'formClass' => CreateProductForm::class,
                'isCreate' => true,
                'view' => 'form',
                'redirectUrl' => function (Product $product) {
                    return '/admin/products/update/' . $product->id;
                },
            ],
            'update' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Product::class,
                'formConvertor' => UpdateProductFormConvertor::class,
                'formClass' => UpdateProductForm::class,
                'isCreate' => false,
                'view' => 'update',
                'redirectUrl' => '/admin/products',
                'viewParams' => function ($params) {
                    $model = Product::findOne($params['id']);

                    $fields = [];
                    foreach ($model->categories as $category) {
                        $fields[$category->id] = $this->productFieldsService()->getFieldsForProduct($model, $category);
                    }

                    return [
                        'fields' => $fields,
                        'mediaForm' => (new ProductMediaFormConvertor())->loadFormFromModel($model),
                        'seoFormModel' => (new ProductSeoFormConvertor())->loadFormFromModel($model)
                    ];
                },
            ],

            'save-seo' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Product::class,
                'formClass' => ProductSeoForm::class,
                'formConvertor' => ProductSeoFormConvertor::class,
                'isCreate' => false,
                'redirectUrl' => '/admin/products',
                'view' => null
            ],

            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Product::class
            ]
        ];
    }

    public function actionUpdateParams($id)
    {
        /** @var Product $model */
        $model = Product::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $fields = $this->productFieldsService()->getFieldsForProduct($model);

        $params = \Yii::$app->request->post('fields');

        foreach ($fields as $field) {
            if ($field->getField()->type == FieldType::BOOLEAN) {
                $value = isset($params[$field->getField()->id]);
            } else {
                $value = isset($params[$field->getField()->id]) ? $params[$field->getField()->id] : null;
            }

            $this->productFieldsService()->setProductFieldValue($model, $field->getField(), $value);
        }

        return $this->redirect('/admin/products');
    }

    public function actionMedia($id)
    {
        /** @var Product $model */
        $model = Product::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $form = new ProductMediaForm();

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            $model->video = $form->video;
            $model->save(false, ['video']);

            $this->productMediaService()->setProductImages($model, $form->getImageIds());
        }

        return $this->redirect('/admin/products');
    }
}