<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'Пункты меню';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'headerButtons' => [
        [
            'label' => 'Добавить пункт меню',
            'action' => 'menu/1/create',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'name' => [
            'header' => 'Название',
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function ($item) {
                return $item['plain_label'];
            }
        ],
        [
            'header' => 'URL',
            'format' => 'raw',
            'value' => function ($item) {
                $link = $item['model']->getUrl(true);
                $label = $item['model']->getUrl(false);
                return \yii\helpers\Html::a($label, $link, ['target' => '_blank']);
            }
        ],
    ],

    'actions' => ['move' => 'move', 'edit' => 'update', 'delete' => 'delete'],

    'idGetter' => function ($category) {
        return $category['model']['id'];
    }
]); ?>