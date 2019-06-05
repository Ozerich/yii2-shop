<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \ozerich\shop\modules\admin\filters\FilterProductColor $filterModel
 * @var \yii\web\View $this
 */
$this->title = 'Цвета';

/** @var \ozerich\shop\models\Color[] $colors */
$colors = \ozerich\shop\models\Color::find()->all();

$categoryFilter = ['' => 'Все категории'];
$tree = (new \ozerich\shop\services\categories\CategoriesService())->getTreeAsPlainArray();
foreach ($tree as $id => $item) {
    $categoryFilter[$item['id']] = $item['label'];
}

$colorFilter = ['' => 'Все цвета', '0' => 'Не указан'];
$colors = \ozerich\shop\models\Color::find()->all();
foreach ($colors as $color) {
    $colorFilter[$color->id] = $color->name;
}
?>

<? echo ozerich\admin\widgets\ListPage::widget([
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
        'product_id' => [
            'attribute' => 'product_id',
            'header' => 'Товар',
            'value' => function (\ozerich\shop\models\ProductImage $productImage) {
                return $productImage->product ? $productImage->product->name : null;
            }
        ],
        'category_id' => [
            'attribute' => 'category_id',
            'header' => 'Категория',
            'value' => function (\ozerich\shop\models\ProductImage $productImage) {
                return $productImage->product->category ? $productImage->product->category->name : null;
            },
            'filter' => \yii\helpers\Html::dropDownList('FilterProductColor[category_id]', $filterModel->category_id, $categoryFilter, ['class' => 'form-control']),
        ],
        'image_id' => [
            'header' => 'Картинка',
            'attribute' => 'image_id',
            'format' => 'raw',
            'value' => function (ozerich\shop\models\ProductImage $product) {
                return '<img src="' . $product->image->getUrl('preview') . '">';
            }
        ],
        'color' => [
            'attribute' => 'color_id',
            'format' => 'raw',
            'header' => 'Цвет',
            'value' => function (\ozerich\shop\models\ProductImage $productImage) use ($colors) {
                $result = '<select class="form-control js-color" data-image-id="' . $productImage->id . '"><option value="">Не указан</option>';

                foreach ($colors as $color) {
                    $result .= '<option ' . ($productImage->color_id == $color->id ? 'selected' : '') . ' value="' . $color->id . '">' . $color->name . '</option>';
                }

                return $result . '</select>';
            },
            'filter' => \yii\helpers\Html::dropDownList('FilterProductColor[color_id]', $filterModel->color_id, $colorFilter, ['class' => 'form-control']),
        ]
    ]
]); ?>

<script>
  $('body').on('change', '.js-color', function () {
    $.post('/admin/products/change-color', {
      image_id: $(this).data('image-id'),
      color_id: $(this).val()
    });
  });
</script>