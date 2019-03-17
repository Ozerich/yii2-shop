<?php

namespace ozerich\shop\modules\admin\controllers;

use ozerich\shop\models\Field;
use ozerich\shop\models\FieldGroup;
use ozerich\admin\actions\CreateOrUpdateAction;
use ozerich\admin\actions\DeleteAction;
use ozerich\admin\actions\ListAction;
use ozerich\admin\controllers\base\AdminController;

class FieldsController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => ListAction::class,
                'query' => Field::find(),
                'view' => 'index'
            ],
            'create' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Field::class,
                'isCreate' => true,
                'view' => 'form',
                'redirectUrl' => '/admin/fields'
            ],
            'update' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => Field::class,
                'isCreate' => false,
                'view' => 'form',
                'redirectUrl' => '/admin/fields'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Field::class
            ],

            'groups' => [
                'class' => ListAction::class,
                'query' => FieldGroup::find(),
                'view' => 'groups/index'
            ],
            'create-group' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => FieldGroup::class,
                'isCreate' => true,
                'view' => 'groups/form',
                'redirectUrl' => '/admin/fields/groups'
            ],
            'update-group' => [
                'class' => CreateOrUpdateAction::class,
                'modelClass' => FieldGroup::class,
                'isCreate' => false,
                'view' => 'groups/form',
                'redirectUrl' => '/admin/fields/groups'
            ],
            'delete-group' => [
                'class' => DeleteAction::class,
                'modelClass' => FieldGroup::class
            ]
        ];
    }
}