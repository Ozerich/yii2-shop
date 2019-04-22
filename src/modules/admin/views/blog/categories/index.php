<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'Категории блога';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'headerButtons' => [
        [
            'label' => 'Добавить категорию',
            'action' => 'blog/create-category',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'name' => [
            'header' => 'Название',
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function ($category) {
                return $category['plain_label'];
            }
        ],
        [
            'header' => 'URL алиас',
            'format' => 'raw',
            'value' => function ($category) {
                $link = $category['model']->getUrl(true);
                $label = $category['model']->getUrl(false);
                return \yii\helpers\Html::a($label, $link, ['target' => '_blank']);
            }
        ],
    ],
    'actions' => ['edit' => 'update-category', 'delete' => 'delete-category'],

    'idGetter' => function ($category) {
        return $category['model']['id'];
    }
]); ?>