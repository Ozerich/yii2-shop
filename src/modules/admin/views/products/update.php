<? /**
 * @var \yii\web\View $this
 *
 * @var ozerich\shop\structures\ProductField[] $fields
 * @var ozerich\shop\models\Product $model
 * @var ozerich\shop\modules\admin\forms\UpdateProductForm $formModel
 * @var ozerich\shop\modules\admin\forms\ProductSeoForm $seoModelForm
 * @var ozerich\shop\modules\admin\forms\ProductMediaForm $mediaForm
 */

$this->title = 'Редактировать товар'
?>

<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#main" data-toggle="tab">Основные параметры</a></li>
    <li><a href="#params" data-toggle="tab">Характеристики</a></li>
    <li><a href="#media" data-toggle="tab">Медиа</a></li>
    <li><a href="#seo" data-toggle="tab">SEO параметры</a></li>
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
    <div class="tab-pane" id="seo">
        <?= $this->render('update/_tab_seo', [
            'model' => $model,
            'formModel' => $seoFormModel
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