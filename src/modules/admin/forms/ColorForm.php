<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Color;

class ColorForm extends Color
{
    public $name;

    public $type;

    public $color;

    public $image_id;

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'type' => 'Тип'
        ]);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['type', 'required']
        ]);
    }
}