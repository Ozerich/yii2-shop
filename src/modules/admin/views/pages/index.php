<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'Страницы';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'headerButtons' => [
        [
            'label' => 'Добавить страницу',
            'action' => 'pages/create',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'title',
        'url' => [
            'attribute' => 'url',
            'format' => 'raw',
            'value' => function (\app\models\Page $model) {
                return \yii\helpers\Html::a($model->url, $model->getUrl(true), ['target' => '_blank']);
            }
        ],
    ],
    'actions' => ['edit' => 'update', 'delete' => 'delete']
]); ?>