<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'Товары';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $filterModel,
    'headerButtons' => [
        [
            'label' => 'Добавить товар',
            'action' => 'products/create',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'name' => [
            'header' => 'Название',
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function (ozerich\shop\models\Product $product) {
                return \yii\helpers\Html::a($product->name, '/admin/products/update/' . $product->id, ['target' => '_blank']);
            }
        ],
        'category_id' => [
            'header' => 'Категория',
            'attribute' => 'category_id',
            'filter' => (new \ozerich\shop\services\categories\CategoriesService())->getTreeAsPlainArray(),
            'value' => function (ozerich\shop\models\Product $product) {
                return $product->category ? $product->category->name : '';
            }
        ],
        'image' => [
            'header' => 'Картинка',
            'attribute' => 'image_id',
            'format' => 'raw',
            'value' => function (ozerich\shop\models\Product $product) {
                return $product->image ? '<img src="' . $product->image->getUrl() . '">' : null;
            }
        ],
        [
            'header' => 'Ссылка',
            'format' => 'raw',
            'value' => function (ozerich\shop\models\Product $product) {
                return \yii\helpers\Html::a($product->getUrl(), $product->getUrl(true), ['target' => '_blank']);
            },
            'attribute' => 'url_alias'
        ],
    ],
    'actions' => ['edit' => 'update', 'delete' => 'delete']
]); ?>