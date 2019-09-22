<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \ozerich\shop\modules\admin\filters\FilterBlogPosts $filterModel
 * @var \yii\web\View $this
 */
$this->title = 'Посты';

$statusFilter = ['' => ''];
foreach (\ozerich\shop\constants\PostStatus::getList() as $id => $name) {
    $statusFilter[$id] = $name;
}
?>


<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $filterModel,

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
        'status' => [
            'attribute' => 'status',
            'value' => function (\ozerich\shop\models\BlogPost $post) {
                return \ozerich\shop\constants\PostStatus::label($post->status);
            },
            'filter' => \yii\helpers\Html::dropDownList('FilterBlogPosts[status]', $filterModel->status, $statusFilter, ['class' => 'form-control']),
        ],
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
    'actions' => ['move' => 'move', 'edit' => 'update', 'delete' => 'delete'],
]); ?>