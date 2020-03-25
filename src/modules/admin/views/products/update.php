<? /**
 * @var \yii\web\View $this
 *
 * @var array $tabs
 * @var ozerich\shop\structures\ProductField[] $fields
 * @var ozerich\shop\models\Product $model
 * @var ozerich\shop\modules\admin\forms\UpdateProductForm $formModel
 * @var ozerich\shop\modules\admin\forms\ProductSeoForm $seoFormModel
 * @var ozerich\shop\modules\admin\forms\ProductMediaForm $mediaForm
 * @var ozerich\shop\modules\admin\forms\ProductConnectionsForm $connectionsForm
 */

$this->title = 'Редактировать товар - ' . $model->name . ' <span class="content-header__label">' . $model->label . '</span>';
?>

<style>
  .content-header__label {
    font-weight: normal;
    font-size: 15px;
    margin-left: 10px;
    display: inline-block;
    color: #888;
  }

  .btn-preview-container {
    float: right;
    margin-top: -40px;
    position: relative;
  }
</style>

<div class="btn-preview-container">
  <a href="<?= $model->getUrl(true) ?>" target="_blank" class="btn btn-success">Просмотреть на сайте</a>
</div>

<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#main" data-toggle="tab">Основные параметры</a></li>
    <li><a href="#params" data-toggle="tab">Характеристики</a></li>
    <li><a href="#media" data-toggle="tab">Медиа</a></li>
    <li><a href="#connections" data-toggle="tab">Связи</a></li>
    <li><a href="#seo" data-toggle="tab">SEO параметры</a></li>
      <? if ($model->type == \ozerich\shop\constants\ProductType::SIMPLE): ?>
        <li><a href="#prices" data-toggle="tab">Цена</a></li>
        <li><a href="#colors" data-toggle="tab">Цвета</a></li>
      <? else: ?>
        <li><a href="#modules" data-toggle="tab">Модули</a></li>
      <? endif; ?>
      <? foreach ($tabs as $ind => $tab): ?>
        <li><a href="#custom_tab_<?= $ind ?>" data-toggle="tab"><?= $tab['label'] ?></a></li>
      <? endforeach; ?>
  </ul>
  <div class="tab-content">
    <div class="active tab-pane" id="main">
        <?= $this->render('update/_tab_main', [
            'model' => $model,
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
    <div class="tab-pane" id="connections">
        <?= $this->render('update/_tab_connections', [
            'model' => $model,
            'formModel' => $connectionsForm
        ]); ?>
    </div>
    <div class="tab-pane" id="seo">
        <?= $this->render('update/_tab_seo', [
            'model' => $model,
            'formModel' => $seoFormModel
        ]); ?>
    </div>

      <? if ($model->type == \ozerich\shop\constants\ProductType::SIMPLE): ?>
        <div class="tab-pane" id="prices">
            <?= $this->render('update/_tab_prices', [
                'model' => $model
            ]); ?>
        </div>
        <div class="tab-pane" id="colors">
            <?= $this->render('update/_tab_colors', [
                'model' => $model
            ]); ?>
        </div>
      <? else: ?>
        <div class="tab-pane" id="modules">
            <?= $this->render('update/_tab_modules', [
                'model' => $model
            ]); ?>
        </div>
      <? endif; ?>

      <? foreach ($tabs as $ind => $tab): ?>
        <div class="tab-pane" id="custom_tab_<?= $ind ?>"><?= $tab['content'] ?></div>
      <? endforeach; ?>

  </div>
</div>
