<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\shop\constants\CategoryType;
use ozerich\shop\models\Category;
use ozerich\shop\models\Manufacture;
use ozerich\shop\modules\api\requests\category\ExportRequest;
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
                    'action' => 'export-preview',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'export',
                    'verbs' => 'POST'
                ],
                [
                    'action' => 'import',
                    'verbs' => 'POST'
                ],
            ]
        ];

        return $behaviors;
    }

    public function actionExportPreview($id, $manufacture_id = 'all', $without_price)
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
                $products = $this->categoriesService()->exportToExcelPreview($category, $manufacture, $without_price);
                return $this->renderPartial('export-preview', compact('products', 'without_price', 'category'));
            }
        }
        return '';
    }

    public function actionExport($id, $manufacture_id = 'all', $without_price){
        $request = new ExportRequest();
        $request->load();
        if ($id && $manufacture_id) {
            $category = Category::findOne(['id' => $id, 'type' => CategoryType::CATALOG]);
            if($manufacture_id == 'ALL') {
                $manufacture = null;
            } else {
                $manufacture = Manufacture::findOne($manufacture_id);
                $manufacture = $manufacture ? $manufacture->id : null;
            }
            if ($category) {
                return $this->categoriesService()->exportToExcel($request->params, $request->filename, $category, $manufacture, $without_price);
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
