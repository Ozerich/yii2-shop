<?php

namespace ozerich\shop\modules\admin\widgets;

use kartik\select2\Select2;
use ozerich\shop\models\Product;

class ProductsSelect2Widget extends Select2
{
    public $excludeId = null;

    public function init()
    {
        parent::init();

        $this->options = [
            'placeholder' => 'Выберите товары',
            'multiple' => true,
            'id' => 'products'
        ];

        $this->pluginOptions = [
            'allowClear' => true,
            'minimumInputLength' => 2,
            'language' => [
                'errorLoading' => new \yii\web\JsExpression("function () { return 'Поиск...'; }"),
            ],
            'ajax' => [
                'url' => '/admin/products/find-ajax?exclude='.$this->excludeId,
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
            ],
        ];

        $this->initValueText = array_map(function ($item) {
            $model = Product::findOne($item);
            return $model->getNameWithManufacture();
        }, $this->value ? $this->value : []);
    }
}