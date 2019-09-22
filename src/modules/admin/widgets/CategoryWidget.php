<?php

namespace ozerich\shop\modules\admin\widgets;

use ozerich\shop\constants\CategoryType;
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
    public $onlyCatalog = false;

    /**
     * @var bool
     */
    public $multiple = false;

    /**
     * @var boolean
     */
    public $placeholder = false;


    /**
     * @var integer
     */
    public $exclude = null;

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

        $tree = $this->categoriesService()->getTreeAsArray();

        foreach ($tree as $id => $item) {
            if ($item['model']['id'] == $this->exclude) {
                continue;
            }

            if ($this->onlyCatalog && $item['model']['type'] != CategoryType::CATALOG) {
                continue;
            }

            $result[$item['model']['id']] = $item['plain_label'];

        }

        return $result;
    }

    public function run()
    {
        return Html::activeDropDownList($this->model, $this->attribute, $this->getDropdownItems(), $this->getOptions());
    }
}