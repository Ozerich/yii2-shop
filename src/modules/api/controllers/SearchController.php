<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\shop\models\Product;
use ozerich\shop\modules\api\models\ProductSearchDTO;
use ozerich\shop\traits\ServicesTrait;

class SearchController extends Controller
{
    use ServicesTrait;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'index',
                    'verbs' => 'GET'
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionIndex($query)
    {
        $items = $this->searchService()->searchProducts($query);

        return array_map(function (Product $product) {
            return (new ProductSearchDTO($product))->toJSON();
        }, $items);
        return [];
    }
}