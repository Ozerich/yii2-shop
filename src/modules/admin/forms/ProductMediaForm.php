<?php

namespace ozerich\shop\modules\admin\forms;

use yii\base\Model;

class ProductMediaForm extends Model
{
    public $video;

    public $images;

    public $image_texts;

    public $schema_image_id;

    public function attributeLabels()
    {
        return [
            'video' => 'Видео',
            'images' => 'Картинки',
            'images' => 'Картинка-схема',
        ];
    }

    public function rules()
    {
        return [
            [['images', 'video', 'image_texts'], 'safe'],
            [['schema_image_id'], 'integer']
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
