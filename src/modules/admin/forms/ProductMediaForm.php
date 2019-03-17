<?php

namespace ozerich\shop\modules\admin\forms;

use yii\base\Model;

class ProductMediaForm extends Model
{
    public $video;

    public $images;

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
            [['images', 'video'], 'safe']
        ];
    }

    public function getImageIds()
    {
        return empty($this->images) ? [] : explode(',', $this->images);
    }
}