<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\admin\controllers\base\AdminController;
use ozerich\shop\plugins\ActionsStorage;
use ozerich\shop\plugins\PagesStorage;
use yii\base\Action;
use yii\filters\Cors;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PluginController extends AdminController
{
    public function behaviors()
    {
        return [
            [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => [],
                ]
            ],
        ];
    }

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

    public function actionPage($plugin, $alias)
    {
        $page = PagesStorage::get($plugin, $alias);

        if (!$page) {
            throw new NotFoundHttpException();
        }

        $this->view->title = $page->pageTitle();

        $content = $page->render();

        if (\Yii::$app->response->format == Response::FORMAT_JSON) {
            return $content;
        }

        if ($content instanceof Response) {
            return $content;
        }

        return $this->render('page', [
            'content' => $content
        ]);
    }
}