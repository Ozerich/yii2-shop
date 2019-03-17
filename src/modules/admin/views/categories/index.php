<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'Товарные категории';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'headerButtons' => [
        [
            'label' => 'Добавить категорию',
            'action' => 'categories/create',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'name' => [
            'header' => 'Название',
            'attribute' => 'name',
            'value' => function (\app\models\Category $category) {
                return $category->parent_id == null ? $category->name : '-------- ' . $category->name;
            }
        ],
        'url_alias',
        'items' => [
            'header' => 'Товары',
            'format' => 'raw',
            'value' => function (\app\models\Category $category) {
                return '<a href="/admin/products?FilterProduct[category_id]=' . $category->id . '" class="">Товары ('.$category->getProductsCount().')</a>';
            }
        ],
    ],
    'actions' => ['edit' => 'update', 'delete' => 'delete']
]); ?>