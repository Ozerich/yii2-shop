<?php

namespace ozerich\shop\modules\admin\forms\settings;

use yii\base\Model;

class HomeSettingsForm extends Model
{
    public $page_title;

    public $meta_description;

    public $meta_image_id;

    public $content;

    public function rules()
    {
        return [
            [['page_title', 'meta_description', 'meta_image_id', 'content'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'page_title' => 'Заголовок страницы (тег title)',
            'meta_description' => 'SEO описание',
            'meta_image_id' => 'OG картинка',
            'content' => 'Текстовый блок'
        ];
    }
}