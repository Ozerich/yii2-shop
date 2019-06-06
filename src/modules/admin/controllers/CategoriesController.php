<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\constants\CategoryType;
use ozerich\shop\models\Category;
use ozerich\shop\models\CategoryDisplay;
use ozerich\shop\models\Product;
use ozerich\shop\modules\admin\forms\CategoryChangeTypeToCatalogForm;
use ozerich\shop\modules\admin\forms\CategoryChangeTypeToConditionalForm;
use ozerich\shop\modules\admin\forms\CategorySeoForm;
use ozerich\shop\modules\admin\forms\CategorySeoFormConvertor;
use ozerich\shop\traits\ServicesTrait;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class CategoriesController extends AdminController
{
    use ServicesTrait;

    public function actions()
    {
        return [
            'index' => [
                'class' => ListAction::class,
                'models' => $this->categoriesService()->getTreeAsArray(),
                'pageSize' => -1,
                'view' => 'index'
            ],
            'seo' => [
                'class' => ListAction::class,
                'models' => $this->categoriesService()->getTreeAsArray(),
                'pageSize' => -1,
                'view' => 'seo'
            ],
            'create' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Category::class,
                'isCreate' => true,
                'view' => 'create',
                'redirectUrl' => function (Category $category) {
                    return '/admin/categories/update/' . $category->id;
                },
            ],
            'update' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Category::class,
                'isCreate' => false,
                'view' => 'update',
                'redirectUrl' => '/admin/categories',
                'viewParams' => function ($params) {
                    return [
                        'seoFormModel' => (new CategorySeoFormConvertor())->loadFormFromModel(Category::findOne($params['id']))
                    ];
                }
            ],
            'save-seo' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Category::class,
                'formClass' => CategorySeoForm::class,
                'formConvertor' => CategorySeoFormConvertor::class,
                'isCreate' => false,
                'redirectUrl' => '/admin/categories',
                'view' => null
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Category::class
            ]
        ];
    }

    public function actionChangeType($id)
    {
        $model = Category::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if (\Yii::$app->request->isPost) {
            if ($model->type == CategoryType::CONDITIONAL) {
                $model->type = CategoryType::CATALOG;
                $model->save(false, ['type']);
                return $this->redirect('/admin/categories/update/' . $model->id);
            } else {
                $formModel = new CategoryChangeTypeToConditionalForm();
                if ($formModel->load(\Yii::$app->request->post())) {
                    if (\Yii::$app->request->isAjax) {
                        \Yii::$app->response->format = Response::FORMAT_JSON;
                        return ActiveForm::validate($formModel);
                    }

                    if ($formModel->validate()) {
                        Product::updateAll(['category_id' => $formModel->category_id], ['category_id' => $model->id]);

                        $model->type = CategoryType::CONDITIONAL;
                        $model->save(false, ['type']);

                        return $this->redirect('/admin/categories/update/' . $model->id);
                    }
                }
            }
        }

        if ($model->type == CategoryType::CATALOG) {
            $formModel = new CategoryChangeTypeToConditionalForm();

            return $this->render('change-type-to-conditional', [
                'model' => $model,
                'formModel' => $formModel
            ]);
        } else {
            return $this->render('change-type-to-catalog', [
                'model' => $model
            ]);
        }
    }

    public function actionAppearance($id)
    {
        $model = Category::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        CategoryDisplay::deleteAll([
            'parent_id' => $model->id
        ]);

        $display = \Yii::$app->request->post('display');
        $position = \Yii::$app->request->post('position');

        foreach (array_keys($display) as $category_id) {
            $item = new CategoryDisplay();
            $item->parent_id = $model->id;
            $item->category_id = $category_id;
            $item->position = isset($position[$category_id]) ? (isset($position[$category_id]) ? $position[$category_id] : 0) : 0;
            if (!$item->position) {
                $item->position = 0;
            }
            $item->save();
        }

        return $this->redirect('/admin/categories/update/' . $model->id);
    }

    public function actionUpdateSeo($id)
    {
        $model = Category::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $model->seo_title = \Yii::$app->request->post('title');
        $model->seo_description = \Yii::$app->request->post('description');

        $model->save(false, ['seo_title', 'seo_description']);
    }
}
