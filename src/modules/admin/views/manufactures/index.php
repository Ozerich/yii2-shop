<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'Производители';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'headerButtons' => [
        [
            'label' => 'Добавить производителя',
            'action' => 'manufactures/create',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'name',
        'image' => [
            'header' => 'Картинка',
            'attribute' => 'image_id',
            'format' => 'raw',
            'value' => function (ozerich\shop\models\Manufacture $product) {
                return $product->image ? '<img src="' . $product->image->getUrl() . '">' : null;
            }
        ],
        'url' => [
            'attribute' => 'url_alias',
            'format' => 'raw',
            'value' => function (ozerich\shop\models\Manufacture $model) {
                return \yii\helpers\Html::a($model->getUrl(false), $model->getUrl(true), ['target' => '_blank']);
            }
        ],
    ],
    'actions' => ['edit' => 'update', 'delete' => 'delete']
]); ?>