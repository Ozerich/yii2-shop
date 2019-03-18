<?php

namespace ozerich\shop\modules\api\controllers;

use app\modules\api\requests\order\SubmitRequest;
use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;

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