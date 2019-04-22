<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\api\controllers\Controller;
use ozerich\api\filters\AccessControl;
use ozerich\api\response\ArrayResponse;
use ozerich\api\response\ModelResponse;
use ozerich\shop\models\BlogCategory;
use ozerich\shop\models\BlogPost;
use ozerich\shop\modules\api\models\BlogCategoryDTO;
use ozerich\shop\modules\api\models\BlogPostDTO;
use ozerich\shop\modules\api\models\BlogPostFullDTO;
use yii\web\NotFoundHttpException;

class BlogController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'action' => 'categories',
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

        return new ArrayResponse(BlogPost::findByParent($parent)->all(), BlogPostDTO::class);
    }

    public function actionPost($id)
    {
        /** @var BlogPost $model */
        $model = BlogPost::findOne($id);
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


}