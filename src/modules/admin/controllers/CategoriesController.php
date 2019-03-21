<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\models\Category;
use ozerich\shop\modules\admin\forms\CategorySeoForm;
use ozerich\shop\modules\admin\forms\CategorySeoFormConvertor;
use ozerich\shop\traits\ServicesTrait;

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
            'create' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Category::class,
                'isCreate' => true,
                'view' => 'create',
                'redirectUrl' => '/admin/categories'
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
}