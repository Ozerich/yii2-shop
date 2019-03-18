<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\shop\modules\api\requests\order\SubmitRequest;

class OrderController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'submit',
                    'verbs' => 'POST'
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionSubmit()
    {
        $request = new SubmitRequest();
        $request->load();

        return [
            'success' => true
        ];
    }
}