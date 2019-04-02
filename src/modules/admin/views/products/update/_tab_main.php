<?
/**
 * @var ozerich\shop\models\Product $model
 * @var ozerich\shop\structures\ProductField[] $fields
 */
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false
]); ?>

<div class="row">
  <div class="col-xs-9">
      <?= $form->field($formModel, 'name')->textInput(); ?>
  </div>
  <div class="col-xs-3">
      <?= $form->field($formModel, 'sku')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'url_alias')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'category_id')->widget(\ozerich\shop\modules\admin\widgets\CategoryWidget::class, [
          'multiple' => true
      ]); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'image_id')->widget(ozerich\shop\modules\admin\widgets\ImageWidget::class, [
          'scenario' => 'product'
      ]); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'text')->widget(\ozerich\admin\widgets\TinyMce::class, ['options' => ['rows' => 35]]); ?>
  </div>
</div>

<div class="row">
  <div class="col-xs-3">
    <div style="margin-top: 30px;">
        <?= $form->field($formModel, 'popular')->checkbox(); ?>
    </div>
  </div>

  <div class="col-xs-3">
    <div style="margin-top: 30px;">
        <?= $form->field($formModel, 'sale_disabled')->checkbox(); ?>
    </div>
  </div>

  <div class="col-xs-6" id="sale-disabled-text_wrapper" style="display: <?=$formModel->sale_disabled ? 'block' : 'none'?>">
      <?= $form->field($formModel, 'sale_disabled_text')->textInput(); ?>
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
      <?= $form->field($formModel, 'schema_image_id')->widget(ozerich\shop\modules\admin\widgets\ImageWidget::class, [
          'scenario' => 'product'
      ]); ?>
  </div>
</div>

<?= $this->render('_box_footer'); ?>

<?php
\yii\widgets\ActiveForm::end();
?>

<script>
  $('#updateproductform-sale_disabled').on('change', function () {
    $('#sale-disabled-text_wrapper').toggle($(this).is(':checked'));
  });
</script>
