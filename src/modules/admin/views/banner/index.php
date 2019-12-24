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

<?php echo ListPage::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'headerButtons' => [
        [
            'label' => 'Добавить баннер',
            'action' => 'banner/create',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
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
                return "<a href='". $banner->url . "' target='_blank'>Открыть ссылку</a>";
            }
        ]
    ],
    'actions' => ['move' => 'move', 'edit' => 'update', 'delete' => 'delete']
]); ?>
