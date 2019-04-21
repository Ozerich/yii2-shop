<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\models\Category;
use ozerich\shop\modules\admin\forms\settings\HomeSettingsForm;
use ozerich\shop\modules\admin\forms\settings\HomeSettingsFormConvertor;

class SettingsController extends AdminController
{
    public function actions()
    {
        return [
            'home' => [
                'class' => CreateOrUpdateAction::class,
                'formClass' => HomeSettingsForm::class,
                'formConvertor' => HomeSettingsFormConvertor::class,
                'view' => 'home'
            ],
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
}