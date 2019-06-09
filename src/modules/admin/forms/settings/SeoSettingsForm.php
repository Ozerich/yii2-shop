<?php

namespace ozerich\shop\modules\admin\forms\settings;

use yii\base\Model;

class SeoSettingsForm extends Model
{
    public $products_title_template;

    public $products_description_template;

    public function rules()
    {
        return [
            [['products_title_template', 'products_description_template'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'products_title_template' => 'Шаблон заголовка страницы для товаров',
            'products_description_template' => 'Шаблон Meta Description для товаров',
        ];
    }
}