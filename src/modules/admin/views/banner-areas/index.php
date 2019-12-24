<? /**
 * @var ActiveDataProvider $dataProvider
 * @var View $this
 */

use ozerich\admin\widgets\ListPage;
use ozerich\shop\models\BannerAreas;
use ozerich\shop\models\Banners;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Зоны размещения баннеров';
?>

<?php echo ListPage::widget([
    'dataProvider' => $dataProvider,
    'headerButtons' => [
        [
            'label' => 'Создать новую зону',
            'action' => 'banner-areas/create',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'name',
        'alias',
        [
            'attribute' => 'Баннеры',
            'format' => 'raw',
            'value' => function(BannerAreas $area) {
                $count = Banners::find()->where(['area_id' => $area->id])->count();
                return Html::a("Баннеры ($count)", ['/admin/banner', 'BannerSearch[area_id]' => $area->id]);
            }
        ]
    ],
    'actions' => ['edit' => 'update', 'delete' => 'delete']
]); ?>
