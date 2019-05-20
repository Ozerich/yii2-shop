<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'Коллекции';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'headerButtons' => [
        [
            'label' => 'Создать коллекцию',
            'action' => 'collections/create',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'title',
        'image' => [
            'header' => 'Картинка',
            'attribute' => 'image_id',
            'format' => 'raw',
            'value' => function (ozerich\shop\models\ProductCollection $product) {
                return $product->image ? '<img src="' . $product->image->getUrl() . '">' : null;
            }
        ],
        'url' => [
            'header' => 'URL',
            'format' => 'raw',
            'value' => function (ozerich\shop\models\ProductCollection $model) {
                return \yii\helpers\Html::a($model->getUrl(false), $model->getUrl(true), ['target' => '_blank']);
            }
        ],
        'manufacture' => [
            'header' => 'Производитель',
            'attribute' => 'manufacture.name',
            'format' => 'raw',
        ],
        [
            'header' => 'Товары',
            'format' => 'raw',
            'value' => function (\ozerich\shop\models\ProductCollection $productCollection) {
                return \yii\helpers\Html::a('Товары (' . $productCollection->getProducts()->count() . ')', '/admin/products?FilterProduct[collection_id]=' . $productCollection->id, ['target' => '_blank']);
            }
        ],
    ],
    'actions' => ['edit' => 'update', 'delete' => 'delete']
]); ?>