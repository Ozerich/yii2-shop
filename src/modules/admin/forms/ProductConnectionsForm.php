<?php

namespace ozerich\shop\modules\admin\forms;

use yii\base\Model;

class ProductConnectionsForm extends Model
{
    public $collection_id;

    public $category_id;

    public $manufacture_id;

    public $same;

    public $priority = [];

    public $two_side = [];

    public function attributeLabels()
    {
        return [
            'collection_id' => 'Коллекция',
            'manufacture_id' => 'Производитель',
            'category_id' => 'Категория',
            'same' => 'Похожие товары',
            'priority' => 'Приоритет похожих товаров',
            'two_side' => 'Связь',
        ];
    }

    public function rules()
    {
        return [
            [['collection_id', 'manufacture_id', 'category_id'], 'integer'],
            [['same', 'priority', 'two_side'], 'safe']
        ];
    }
}
