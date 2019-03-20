<?php

namespace ozerich\shop\modules\admin\widgets;

use ozerich\shop\traits\ServicesTrait;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class CategoryWidget extends InputWidget
{
    use ServicesTrait;

    /**
     * @var boolean
     */
    public $allowEmptyValue = false;

    /**
     * @var boolean
     */
    public $placeholder = false;

    private function getOptions()
    {
        $result = [
            'class' => 'form-control'
        ];

        if ($this->placeholder) {
            $result['prompt'] = 'Выберите категорию';
        }

        return $result;
    }

    private function getDropdownItems()
    {
        $result = [];

        if ($this->allowEmptyValue) {
            $result[''] = 'Без категории';
        }

        return array_merge($result, $this->categoriesService()->getTreeAsPlainArray());
    }

    public function run()
    {
        return Html::activeDropDownList($this->model, $this->attribute, $this->getDropdownItems(), $this->getOptions());
    }
}