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
        'items' => [
            'header' => 'Товары',
            'format' => 'raw',
            'value' => function ($category) {
                return '<a href="/admin/products?FilterProduct[category_id]=' . $category['model']->id . '" class="">Товары (' . $category['model']->getProductsCount() . ')</a>';
            }
        ],
    ],
    'actions' => ['edit' => 'update', 'delete' => 'delete'],

    'idGetter' => function ($category) {
        return $category['model']['id'];
    }
]); ?>