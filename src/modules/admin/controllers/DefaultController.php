<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\actions\TinymceUploadAction;
use ozerich\admin\controllers\base\AdminController;

class DefaultController extends AdminController
{
    public function actions()
    {
        return [
            'upload' => [
                'class' => \ozerich\filestorage\actions\UploadAction::class,
            ],
            'upload-tinymce' => [
                'class' => TinymceUploadAction::class,
                'scenario' => 'default'
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect('/admin/categories');
    }
}