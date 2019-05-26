<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'Цвета';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'headerButtons' => [
        [
            'label' => 'Добавить цвет',
            'action' => 'settings/create-color',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'name',
        [
            'attribute' => 'image_id',
            'format' => 'raw',
            'value' => function (\ozerich\shop\models\Color $color) {
                return $color->image ? yii\helpers\Html::img($color->image->getUrl()) : null;
            }
        ],
        [
            'attribute' => 'color',
            'format' => 'raw',
            'value' => function (\ozerich\shop\models\Color $color) {
                return '<div style="width: 100px; height: 30px; background: ' . $color->color . '"></div>';
            }
        ]
    ],
    'actions' => ['edit' => 'update-color', 'delete' => 'delete-color']
]); ?>