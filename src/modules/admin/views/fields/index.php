<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'Поля';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'headerButtons' => [
        [
            'label' => 'Добавить поле',
            'action' => 'fields/create',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'name' => [
            'header' => 'Название',
            'attribute' => 'name'
        ],
        'type' => [
            'header' => 'Тип',
            'attribute' => 'type',
            'value' => function (ozerich\shop\models\Field $item) {
                return ozerich\shop\constants\FieldType::label($item->type);
            }
        ],
        'values' => [
            'header' => 'Значения',
            'attribute' => 'values',
            'value' => function (ozerich\shop\models\Field $item) {
                return implode(", ", $item->values);
            }
        ],
    ],
    'actions' => ['edit' => 'update', 'delete' => 'delete']
]); ?>