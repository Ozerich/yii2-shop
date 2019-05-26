<?php

namespace ozerich\shop\modules\admin\forms;

use ozerich\shop\models\Color;
use ozerich\shop\traits\ServicesTrait;
use yii\base\Model;

class ColorFormConvertor extends Model
{
    use ServicesTrait;

    public function loadFormFromModel(Color $color)
    {
        $form = new ColorForm();

        $form->type = empty($color->color) ? 'IMAGE' : 'COLOR';
        $form->color = $color->color;
        $form->image_id = $color->image_id;
        $form->name = $color->name;

        return $form;
    }

    public function saveModelFromForm(Color $model, ColorForm $form)
    {
        $model->name = $form->name;

        if ($form->type == 'IMAGE') {
            $model->image_id = $form->image_id;
            $model->color = null;
        } else {
            $model->image_id = null;
            $model->color = $form->color;
        }

        $model->save();

        return true;
    }
}