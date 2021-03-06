<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\models\Category;
use ozerich\shop\models\Color;
use ozerich\shop\models\Currency;
use ozerich\shop\modules\admin\forms\ColorForm;
use ozerich\shop\modules\admin\forms\ColorFormConvertor;
use ozerich\shop\modules\admin\forms\settings\BlogSettingsForm;
use ozerich\shop\modules\admin\forms\settings\BlogSettingsFormConvertor;
use ozerich\shop\modules\admin\forms\settings\HomeSettingsForm;
use ozerich\shop\modules\admin\forms\settings\HomeSettingsFormConvertor;
use ozerich\shop\modules\admin\forms\settings\SeoSettingsForm;
use ozerich\shop\modules\admin\forms\settings\SeoSettingsFormConvertor;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SettingsController extends AdminController
{
    public function actions()
    {
        return [
            'home' => [
                'class' => CreateOrUpdateAction::class,
                'formClass' => HomeSettingsForm::class,
                'formConvertor' => HomeSettingsFormConvertor::class,
                'view' => 'home',
                'redirectUrl' => '/admin/settings/home'
            ],
            'blog' => [
                'class' => CreateOrUpdateAction::class,
                'formClass' => BlogSettingsForm::class,
                'formConvertor' => BlogSettingsFormConvertor::class,
                'view' => 'blog',
                'redirectUrl' => '/admin/settings/blog'
            ],
            'seo' => [
                'class' => CreateOrUpdateAction::class,
                'formClass' => SeoSettingsForm::class,
                'formConvertor' => SeoSettingsFormConvertor::class,
                'view' => 'seo',
                'redirectUrl' => '/admin/settings/seo'
            ],
            'currencies' => [
                'class' => ListAction::class,
                'query' => Currency::find(),
                'view' => 'currencies/index'
            ],
            'create-currency' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Currency::class,
                'view' => 'currencies/form',
                'isCreate' => true,
                'redirectUrl' => '/admin/settings/currencies'
            ],
            'update-currency' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Currency::class,
                'view' => 'currencies/form',
                'isCreate' => false,
                'redirectUrl' => '/admin/settings/currencies'
            ],
            'delete-currency' => [
                'class' => DeleteAction::class,
                'modelClass' => Currency::class
            ],
            'colors' => [
                'class' => ListAction::class,
                'query' => Color::find(),
                'view' => 'colors/index'
            ],
            'create-color' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Color::class,
                'formClass' => ColorForm::class,
                'formConvertor' => ColorFormConvertor::class,
                'view' => 'colors/form',
                'isCreate' => true,
                'redirectUrl' => '/admin/settings/colors'
            ],
            'update-color' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Color::class,
                'formClass' => ColorForm::class,
                'formConvertor' => ColorFormConvertor::class,
                'view' => 'colors/form',
                'isCreate' => false,
                'redirectUrl' => '/admin/settings/colors'
            ],
            'delete-color' => [
                'class' => DeleteAction::class,
                'modelClass' => Color::class
            ]
        ];
    }

    public function actionHomeCategories()
    {
        Category::updateAll(['home_display' => false, 'home_position' => null]);

        $items = \Yii::$app->request->post('home_display');
        $positions = \Yii::$app->request->post('home_position');
        $items = is_array($items) ? array_keys($items) : [];

        foreach ($items as $category_id) {
            $position = isset($positions[$category_id]) ? $positions[$category_id] : 99;

            /** @var Category $category */
            $category = Category::findOne($category_id);
            if (!$category) {
                continue;
            }

            $category->home_display = true;
            $category->home_position = $position;

            $category->save(false, ['home_display', 'home_position']);
        }

        return $this->redirect(\Yii::$app->request->getReferrer());
    }

    public function actionChangeRate()
    {
        if (!\Yii::$app->request->isPost || !\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        $currency = Currency::findOne(\Yii::$app->request->post('id'));
        if (!$currency) {
            throw new NotFoundHttpException();
        }

        $currency->rate = \Yii::$app->request->post('value');
        $currency->save(false, ['rate']);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'success' => true
        ];
    }
}