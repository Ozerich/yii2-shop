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
     * @var bool
     */
    public $multiple = false;

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

        if ($this->multiple) {
            $result['multiple'] = true;
            $result['style'] = 'height: 300px';

            if (isset($result['prompt'])) {
                unset($result['prompt']);
            }
        }

        return $result;
    }

    private function getDropdownItems()
    {
        $result = [];

        if ($this->allowEmptyValue) {
            $result[''] = 'Без категории';
        }

        foreach($this->categoriesService()->getTreeAsPlainArray() as $id => $item){
            $result[$id] = $item;
        }

        return $result;
    }

    public function run()
    {
        return Html::activeDropDownList($this->model, $this->attribute, $this->getDropdownItems(), $this->getOptions());
    }
}