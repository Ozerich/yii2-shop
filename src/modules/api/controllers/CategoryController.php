<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\shop\constants\CategoryType;
use ozerich\shop\models\Category;
use ozerich\shop\traits\ServicesTrait;

class CategoryController extends Controller
{
    use ServicesTrait;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'export',
                    'verbs' => 'GET'
                ],
            ]
        ];

        return $behaviors;
    }

    public function actionExport($id)
    {
        if ($id) {
            $category = Category::findOne(['id' => $id, 'type' => CategoryType::CATALOG]);
            if ($category) {
                return $this->categoriesService()->exportToExel($category);
            }
        }
    }

}
