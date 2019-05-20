<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \ozerich\shop\modules\admin\filters\FilterProduct $filterModel
 * @var \yii\web\View $this
 */
$this->title = 'Товары';

$categoryFilter = ['' => 'Все категории'];
$tree = (new \ozerich\shop\services\categories\CategoriesService())->getTreeAsPlainArray();
foreach ($tree as $id => $item) {
    $categoryFilter[$item['id']] = $item['label'];
}

$manufactureFilter = ['' => 'Все производители'];
foreach (ozerich\shop\models\Manufacture::getList() as $id => $name) {
    $manufactureFilter[$id] = $name;
}


$collectionFilter = ['' => 'Все коллекции'];
foreach (ozerich\shop\models\ProductCollection::getList() as $id => $name) {
    $collectionFilter[$id] = $name;
}

$columns = [
    'name' => [
        'header' => 'Название',
        'attribute' => 'name',
        'format' => 'raw',
        'value' => function (ozerich\shop\models\Product $product) {
            $result = \yii\helpers\Html::a($product->name . (!empty($product->label) ? ' - <small>' . $product->label . '</small>' : ''), '/admin/products/update/' . $product->id, ['target' => '_blank']);;
            return $result;
        }
    ],
    'manufacture' => [
        'header' => 'Производитель',
        'attribute' => 'manufacture.name',
        'format' => 'raw',
        'filter' => \yii\helpers\Html::dropDownList('FilterProduct[manufacture_id]', $filterModel->manufacture_id, $manufactureFilter, ['class' => 'form-control']),
    ],
    'collection' => [
        'header' => 'Коллекция',
        'attribute' => 'collection.title',
        'format' => 'raw',
        'value' => function (\ozerich\shop\models\Product $product) {
            return $product->collection ? $product->collection->title : '';
        },
        'filter' => \yii\helpers\Html::dropDownList('FilterProduct[collection_id]', $filterModel->collection_id, $collectionFilter, ['class' => 'form-control']),
    ],
    'category_id' => [
        'header' => 'Категория',
        'format' => 'raw',
        'filter' => \yii\helpers\Html::dropDownList('FilterProduct[category_id]', $filterModel->category_id, $categoryFilter, ['class' => 'form-control']),
        'value' => function (ozerich\shop\models\Product $product) {
            return $product->category ? $product->category->name : null;
        }
    ]
];

if ($filterModel->category_id) {
    $columns[] = [
        'header' => 'Приоритет',
        'format' => 'raw',
        'value' => function (\ozerich\shop\models\Product $product) {
            return '<input type="number" value="' . ($product->popular_weight ? $product->popular_weight : '') . '"  style="width: 70px; text-align: center" class="form-control js-priority-input" data-id="' . $product->id . '">';
        }
    ];
}

$columns = array_merge($columns, [
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
]);
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
    'columns' => $columns,
    'actions' => [
        'copy' => 'copy',
        'edit' => 'update',
        'delete' => 'delete'
    ]
]); ?>

<script>
  $('body').on('keyup', '.js-priority-input', function () {
    $.post('/admin/products/' + $(this).data('id') + '/weight', {
      value: +$(this).val()
    });
  }).on('keyup', '.js-price-input', function () {
    $.post('/admin/products/' + $(this).data('id') + '/price', {
      value: +$(this).val()
    });
  });
</script>
