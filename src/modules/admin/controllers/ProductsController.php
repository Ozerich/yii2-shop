<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\constants\FieldType;
use ozerich\shop\models\Color;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductImage;
use ozerich\shop\modules\admin\filters\FilterProduct;
use ozerich\shop\modules\admin\filters\FilterProductColor;
use ozerich\shop\modules\admin\forms\CreateProductForm;
use ozerich\shop\modules\admin\forms\CreateProductFormConvertor;
use ozerich\shop\modules\admin\forms\ProductConnectionsForm;
use ozerich\shop\modules\admin\forms\ProductConnectionsFormConvertor;
use ozerich\shop\modules\admin\forms\ProductMediaForm;
use ozerich\shop\modules\admin\forms\ProductMediaFormConvertor;
use ozerich\shop\modules\admin\forms\ProductSeoForm;
use ozerich\shop\modules\admin\forms\ProductSeoFormConvertor;
use ozerich\shop\modules\admin\forms\UpdateProductForm;
use ozerich\shop\modules\admin\forms\UpdateProductFormConvertor;
use ozerich\shop\traits\ServicesTrait;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProductsController extends AdminController
{
    use ServicesTrait;

    public $enableCsrfValidation = false;

    public function actions()
    {
        return [
            'index' => [
                'class' => ListAction::class,
                'query' => Product::find()->addOrderBy('popular_weight DESC')->addOrderBy('name ASC'),
                'view' => 'index',
                'pageSize' => 100,
                'filterModel' => new FilterProduct(),
            ],
            'colors' => [
                'class' => ListAction::class,
                'query' => ProductImage::find(),
                'view' => 'colors',
                'pageSize' => 100,
                'filterModel' => new FilterProductColor(),
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

                    return [
                        'fields' => $this->productFieldsService()->getFieldsForProduct($model),
                        'mediaForm' => (new ProductMediaFormConvertor())->loadFormFromModel($model),
                        'seoFormModel' => (new ProductSeoFormConvertor())->loadFormFromModel($model),
                        'connectionsForm' => (new ProductConnectionsFormConvertor())->loadFormFromModel($model),
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

            'save-connections' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Product::class,
                'formClass' => ProductConnectionsForm::class,
                'formConvertor' => ProductConnectionsFormConvertor::class,
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
            } else if ($field->getField()->type == FieldType::SELECT) {
                if ($field->getField()->multiple) {
                    $value = isset($params[$field->getField()->id]) ? implode(';', array_keys($params[$field->getField()->id])) : null;
                } else {
                    $value = $params[$field->getField()->id];
                }
            } else {
                $value = isset($params[$field->getField()->id]) ? $params[$field->getField()->id] : null;
            }

            $this->productFieldsService()->setProductFieldValue($model, $field->getField(), $value);
        }

        $this->categoryProductsService()->afterProductParamsChanged($model);

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

            $this->productMediaService()->setProductImages($model, $form->getImageIds(), $form->getImageTexts());
        }

        return $this->redirect('/admin/products');
    }

    public function actionWeight($id)
    {
        /** @var Product $model */
        $model = Product::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $model->popular_weight = \Yii::$app->request->post('value');
        $model->save(false, ['popular_weight']);

        return null;
    }

    public function actionPrice($id)
    {
        /** @var Product $model */
        $model = Product::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if ($model->is_prices_extended) {
            return;
        }

        $model->price = (int)\Yii::$app->request->post('value');
        $model->save(false, ['price']);


        $this->categoryProductsService()->afterProductParamsChanged($model);
        return null;
    }

    public function actionPrices()
    {
        return $this->render('prices');
    }

    public function actionParams()
    {
        return $this->render('params');
    }

    public function actionCopy($id)
    {
        /** @var Product $model */
        $model = Product::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $copy = $this->productBaseService()->createCopy($model);

        return $this->redirect('/admin/products/update/' . $copy->id);
    }

    public function actionFindAjax($q, $exclude = null)
    {
        $query = Product::find()->andWhere('name LIKE :name', [':name' => '%' . $q . '%']);

        if ($exclude) {
            $query->andWhere('products.id <> :exclude_id', [':exclude_id' => $exclude]);
        }

        /** @var Product[] $products */
        $products = $query->all();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $results = [];
        foreach ($products as $result) {
            $results[] = [
                'id' => $result->id,
                'text' => $result->getNameWithManufacture()
            ];
        }

        return [
            'results' => $results
        ];
    }

    public function actionSaveColors($id)
    {
        /** @var Product $model */
        $model = Product::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $colorIds = $this->productColorsService()->getProductColorIds($model);
        $deleted = [];
        foreach ($colorIds as $colorId) {
            if ($colorId) {
                $deleted[$colorId] = true;
            }
        }

        $added = [];

        $colors = \Yii::$app->request->post('color');
        $texts = \Yii::$app->request->post('text');

        foreach ($colors as $imageId => $color) {
            $image = ProductImage::findOne($imageId);
            if (!$image) {
                continue;
            }

            $image->text = $texts[$imageId];
            $image->color_id = $color;

            if ($color) {
                $deleted[$color] = false;
            }

            if ($color && !in_array($color, $colorIds)) {
                $added[] = $color;
                $deleted[$color] = false;
            }

            $image->save();
        }

        $changed = [];
        foreach ($deleted as $color_id => $isDeleted) {
            if ($isDeleted) {
                $changed[] = $color_id;
            }
        }
        $changed = array_values(array_merge($changed, $added));

        $this->productColorsService()->updateCategoriesWithColorIds($changed);

        if (isset($_POST['only-save'])) {
            return $this->redirect('/admin/products/' . $model->id);
        } else {
            return $this->redirect('/admin/products');
        }
    }

    public function actionChangeColor()
    {
        $imageId = \Yii::$app->request->post('image_id');

        $productImage = ProductImage::findOne($imageId);
        if (!$productImage) {
            throw new NotFoundHttpException('Картинки не найдено');
        }

        $oldColor = $productImage->color_id;

        $colorId = \Yii::$app->request->post('color_id');
        if ($colorId == $oldColor) {
            return;
        }

        if($colorId) {
            $color = Color::findOne($colorId);
            if (!$color) {
                throw new NotFoundHttpException('Цвета не найдено');
            }

            $productImage->color_id = $color->id;
        } else{
            $productImage->color_id = null;
        }

        $productImage->save(false, ['color_id']);

        $this->productColorsService()->updateCategoriesWithColorIds([$oldColor]);
        $this->productColorsService()->updateCategoriesWithColorIds([$color->id]);
    }
}