<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\controllers\base\AdminController;

class DefaultController extends AdminController
{
    public function actions()
    {
        return [
            'upload' => [
                'class' => \ozerich\filestorage\actions\UploadAction::class,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect('/admin/categories');
    }
}