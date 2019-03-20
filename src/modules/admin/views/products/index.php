<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \ozerich\shop\modules\admin\filters\FilterProduct $filterModel
 * @var \yii\web\View $this
 */
$this->title = 'Товары';

$categoryFilter = ['' => 'Все категории'];
foreach ((new \ozerich\shop\services\categories\CategoriesService())->getTreeAsPlainArray() as $id => $item) {
    $categoryFilter[$id] = $item;
}
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
            'format' => 'raw',
            'filter' => \yii\helpers\Html::dropDownList('FilterProduct[category_id]', $filterModel->category_id, $categoryFilter, ['class' => 'form-control']),
            'value' => function (ozerich\shop\models\Product $product) {
                return implode('<br/>', array_map(function (\ozerich\shop\models\Category $category) {
                    return $category->getFullName();
                }, $product->categories));
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