<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\CollectionResponse;
use ozerich\shop\constants\SettingOption;
use ozerich\shop\models\Image;
use ozerich\shop\models\Menu;
use ozerich\shop\models\MenuItem;
use ozerich\shop\modules\api\models\MenuItemDTO;
use ozerich\shop\modules\api\responses\misc\HomeResponse;
use ozerich\shop\traits\ServicesTrait;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class MiscController extends Controller
{
    use ServicesTrait;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'home',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'menu',
                    'verbs' => 'GET'
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionHome()
    {
        $response = new HomeResponse();

        $imageId = $this->settingsService()->get(SettingOption::HOME_IMAGE_ID);
        $image = $imageId ? Image::findOne($imageId) : null;

        $response->setSeoParams(
            $this->settingsService()->get(SettingOption::HOME_TITLE),
            $this->settingsService()->get(SettingOption::HOME_DESCRIPTION),
            $image
        );

        $response->setContent($this->settingsService()->get(SettingOption::HOME_CONTENT));

        return $response;
    }

    public function actionMenu($id)
    {
        $menu = Menu::findOne($id);
        if (!$menu) {
            throw new NotFoundHttpException('Меню не найдено');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => MenuItem::findRoot($menu)
        ]);

        return new CollectionResponse($dataProvider, MenuItemDTO::class);
    }
}