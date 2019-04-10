<?php

namespace ozerich\shop\modules\admin\forms;

use yii\base\Model;

class ProductMediaForm extends Model
{
    public $video;

    public $images;

    public $image_texts;

    public function attributeLabels()
    {
        return [
            'video' => 'Видео',
            'images' => 'Картинки'
        ];
    }

    public function rules()
    {
        return [
            [['images', 'video', 'image_texts'], 'safe']
        ];
    }

    public function getImageIds()
    {
        return empty($this->images) ? [] : explode(',', $this->images);
    }

    public function getImageTexts()
    {
        return $this->image_texts;
    }
}