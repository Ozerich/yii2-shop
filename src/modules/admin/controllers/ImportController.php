<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\modules\admin\forms\CategoryChangeTypeToCatalogForm;
use ozerich\shop\traits\ServicesTrait;
use yii\base\DynamicModel;

class ImportController extends AdminController
{
    use ServicesTrait;

    public function actionCategory(){
        $model = new DynamicModel(['file']);
        return $this->render('category', compact('model'));
    }
}
