<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\plugins\ActionsStorage;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class PluginController extends AdminController
{
    public function actionIndex($plugin, $action)
    {
        /** @var Action $actionHandler */
        $actionHandler = ActionsStorage::get($plugin, $action);

        if ($actionHandler) {
            $params = \Yii::$app->request->getQueryParams();

            unset($params['plugin']);
            unset($params['action']);

            return $actionHandler->runWithParams($params);
        } else {
            throw new NotFoundHttpException();
        }
    }
}