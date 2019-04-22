<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'Посты';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'headerButtons' => [
        [
            'label' => 'Добавить пост',
            'action' => 'blog/create',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'title',
        'image_id' => [
            'attribute' => 'image_id',
            'format' => 'raw',
            'value' => function (\ozerich\shop\models\BlogPost $post) {
                return \yii\helpers\Html::img($post->image ? $post->image->getUrl() : null);
            }
        ],
        [
            'header' => 'URL алиас',
            'format' => 'raw',
            'value' => function (\ozerich\shop\models\BlogPost $post) {
                return \yii\helpers\Html::a($post->getUrl(false), $post->getUrl(true), ['target' => '_blank']);
            }
        ],
    ],
    'actions' => ['edit' => 'update', 'delete' => 'delete'],
]); ?>