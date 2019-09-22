<?php

namespace ozerich\shop\modules\admin\widgets;

use kartik\select2\Select2;
use ozerich\shop\models\BlogPost;

class PostsSelect2Widget extends Select2
{
    public $excludeId = null;

    public function init()
    {
        parent::init();

        $this->options = [
            'placeholder' => 'Выберите посты',
            'multiple' => true,
            'id' => 'posts'
        ];

        $this->pluginOptions = [
            'allowClear' => true,
            'minimumInputLength' => 2,
            'language' => [
                'errorLoading' => new \yii\web\JsExpression("function () { return 'Поиск...'; }"),
            ],
            'ajax' => [
                'url' => '/admin/blog/find-ajax?exclude=' . $this->excludeId,
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
            ],
        ];

        $this->initValueText = array_map(function ($item) {
            $model = BlogPost::findOne($item);
            return $model->title;
        }, $this->value ? $this->value : []);
    }
}