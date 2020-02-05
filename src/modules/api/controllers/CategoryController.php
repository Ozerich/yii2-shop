<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\shop\constants\CategoryType;
use ozerich\shop\models\Category;
use ozerich\shop\models\Manufacture;
use ozerich\shop\modules\api\requests\category\ImportRequest;
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
                [
                    'action' => 'import',
                    'verbs' => 'POST'
                ],
            ]
        ];

        return $behaviors;
    }

    public function actionExport($id, $manufacture_id = 'all', $without_price)
    {
        if ($id && $manufacture_id) {
            $category = Category::findOne(['id' => $id, 'type' => CategoryType::CATALOG]);
            if($manufacture_id == 'ALL') {
                $manufacture = null;
            } else {
                $manufacture = Manufacture::findOne($manufacture_id);
                $manufacture = $manufacture ? $manufacture->id : null;
            }
            if ($category) {
                return $this->categoriesService()->exportToExcel($category, $manufacture, $without_price);
            }
        }
    }

    public function actionImport()
    {
        $request = new ImportRequest();
        $request->load($_FILES);
        return $this->categoriesService()->importFromExcel($request->file);
    }

}
