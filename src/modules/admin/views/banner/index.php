<? /**
 * @var ActiveDataProvider $dataProvider
 * @var ActiveDataProvider $searchModel
 * @var View $this
 */

use ozerich\admin\widgets\ListPage;
use ozerich\shop\models\BannerAreas;
use ozerich\shop\models\Banners;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\View;

$this->title = 'Баннеры';
?>

<?php
$actions = ['edit' => 'update', 'delete' => 'delete'];
echo ListPage::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'headerButtons' => [
        [
            'label' => 'Добавить баннер',
            'action' => "banner/create?area=$area",
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'photo_id' => [
            'attribute' => 'Баннер',
            'format' => 'raw',
            'attribute' => 'photo_id',
            'value' => function (Banners $model) {
                $image = $model->photo->getUrl();
                return "<img src='$image' width='60px'/>";
            },
        ],
        'area_id' => [
            'attribute' => 'Зона размещения',
            'format' => 'raw',
            'attribute' => 'area_id',
            'filter' => ArrayHelper::map(BannerAreas::find()->all(), 'id', 'name'),
            'value' => function (Banners $model) {
                return $model->area->name;
            },
        ],
        'title',
        'url' => [
            'attribute' => 'Ссылка',
            'format' => 'raw',
            'value' => function(Banners $banner) {
                return "<a data-toggle='tooltip' title='". $banner->url ."' href='". $banner->url . "' target='_blank'>Открыть ссылку</a>";
            }
        ]
    ],
    'actions' => $showSort ? array_merge(['move' => 'move'], $actions) : $actions
]); ?>
<script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>
