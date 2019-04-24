<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \ozerich\shop\modules\admin\filters\FilterProduct $filterModel
 * @var \yii\web\View $this
 */
$this->title = 'Цены и наличие';

\ozerich\shop\modules\admin\react\PricesAsset::register($this);
?>

<div id="react-app-prices"></div>
