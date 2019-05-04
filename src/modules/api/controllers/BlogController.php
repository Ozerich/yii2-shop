<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\ArrayResponse;
use ozerich\api\response\CollectionResponse;
use ozerich\api\response\ModelResponse;
use ozerich\shop\constants\PostStatus;
use ozerich\shop\constants\SettingOption;
use ozerich\shop\models\BlogCategory;
use ozerich\shop\models\BlogPost;
use ozerich\shop\models\Image;
use ozerich\shop\modules\api\models\BlogCategoryDTO;
use ozerich\shop\modules\api\models\BlogPostDTO;
use ozerich\shop\modules\api\models\BlogPostFullDTO;
use ozerich\shop\modules\api\responses\blog\SettingsResponse;
use ozerich\shop\traits\ServicesTrait;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class BlogController extends Controller
{
    use ServicesTrait;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'settings',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'categories',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'same',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'posts',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'post',
                    'verbs' => 'GET'
                ],
                [
                    'action' => 'category',
                    'verbs' => 'GET'
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionCategories($id = null)
    {
        $parent = null;
        if ($id !== null) {
            $parent = BlogCategory::findOne($id);
            if (!$parent) {
                throw new NotFoundHttpException('Родительской категории не найдено');
            }
        }

        $models = $parent === null ? BlogCategory::findRoot()->all() : BlogCategory::findByParent($parent)->all();

        return new ArrayResponse($models, BlogCategoryDTO::class);
    }

    public function actionPosts($id = null)
    {
        $parent = null;
        if ($id !== null) {
            $parent = BlogCategory::findOne($id);
            if (!$parent) {
                throw new NotFoundHttpException('Родительской категории не найдено');
            }
        }

        if ($parent === null) {
            $query = BlogPost::findByStatus(PostStatus::PUBLISHED);
        } else if (!$parent) {
            $query = BlogPost::findByParent(null, PostStatus::PUBLISHED);
        } else {
            $query = BlogPost::findByParent($parent, PostStatus::PUBLISHED);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return new CollectionResponse($dataProvider, BlogPostDTO::class);
    }

    public function actionPost($alias)
    {
        /** @var BlogPost $model */
        $model = BlogPost::findByAlias($alias, PostStatus::PUBLISHED)->one();
        if (!$model) {
            throw new NotFoundHttpException('Поста не найдено');
        }

        return new ModelResponse($model, BlogPostFullDTO::class);
    }

    public function actionCategory($id)
    {
        /** @var \ozerich\shop\models\BlogCategory $model */
        $model = BlogCategory::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Категории не найдено');
        }

        return new ModelResponse($model, BlogCategoryDTO::class);
    }

    public function actionSettings()
    {
        $response = new SettingsResponse();

        $imageId = $this->settingsService()->get(SettingOption::BLOG_IMAGE_ID);

        $response->setSeoParams(
            $this->settingsService()->get(SettingOption::BLOG_TITLE),
            $this->settingsService()->get(SettingOption::BLOG_DESCRIPTION),
            $imageId ? Image::findOne($imageId) : null
        );

        return $response;
    }

    public function actionSame($id)
    {
        /** @var BlogPost $model */
        $model = BlogPost::findByStatus(PostStatus::PUBLISHED)->andWhere('id=:id', [':id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException('Поста не найдено');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $this->blogService()->getSamePostsQuery($model)
        ]);

        return new CollectionResponse($dataProvider, BlogPostDTO::class);
    }
}