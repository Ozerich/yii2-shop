<? /**
 * @var \yii\web\View $this
 *
 * @var \app\structures\ProductField[] $fields
 * @var \app\models\Product $model
 * @var \app\modules\admin\forms\UpdateProductForm $formModel
 * @var \app\modules\admin\forms\ProductMediaForm $mediaForm
 */

$this->title = 'Редактировать товар'
?>

<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#main" data-toggle="tab">Основные параметры</a></li>
    <li><a href="#params" data-toggle="tab">Характеристики</a></li>
    <li><a href="#media" data-toggle="tab">Медиа</a></li>
      <? if ($model->is_prices_extended): ?>
        <li><a href="#prices" data-toggle="tab">Цены</a></li>
      <? endif; ?>
  </ul>
  <div class="tab-content">
    <div class="active tab-pane" id="main">
        <?= $this->render('update/_tab_main', [
            'formModel' => $formModel
        ]); ?>
    </div>
    <div class="tab-pane" id="params">
        <?= $this->render('update/_tab_params', [
            'fields' => $fields,
            'model' => $model
        ]); ?>
    </div>
    <div class="tab-pane" id="media">
        <?= $this->render('update/_tab_media', [
            'model' => $model,
            'formModel' => $mediaForm
        ]); ?>
    </div>
      <? if ($model->is_prices_extended): ?>
        <div class="tab-pane" id="prices">
            <?= $this->render('update/_tab_prices', [
                'model' => $model
            ]); ?>
        </div>
      <? endif; ?>
  </div>
</div>