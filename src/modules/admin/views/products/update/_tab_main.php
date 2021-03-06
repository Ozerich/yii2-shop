<?
/**
 * @var ozerich\shop\models\Product $model
 * @var \ozerich\shop\modules\admin\forms\UpdateProductForm $formModel
 * @var ozerich\shop\structures\ProductField[] $fields
 */
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false
]); ?>

<div class="row">

  <div class="col-xs-12">
      <?= $form->field($formModel, 'type')->dropDownList(\ozerich\shop\constants\ProductType::list()); ?>
  </div>

  <div class="col-xs-6">
      <?= $form->field($formModel, 'name')->textInput(); ?>
  </div>

  <div class="col-xs-3">
      <?= $form->field($formModel, 'label')->textInput(); ?>
  </div>

  <div class="col-xs-3">
      <?= $form->field($formModel, 'sku')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'url_alias')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'image_id')->widget(ozerich\shop\modules\admin\widgets\ImageWidget::class, [
          'scenario' => 'product'
      ]); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'text')->widget(\ozerich\shop\modules\admin\widgets\TinyMceWidget::class); ?>
  </div>
</div>

<div class="row">
  <div class="col-xs-3">
      <?= $form->field($formModel, 'hidden')->checkbox(); ?>
  </div>
  <div class="col-xs-3">
      <?= $form->field($formModel, 'sale_disabled')->checkbox(); ?>
  </div>
  <div class="col-xs-6" id="sale-disabled-text_wrapper"
       style="display: <?= $formModel->sale_disabled ? 'block' : 'none' ?>">
      <?= $form->field($formModel, 'sale_disabled_text')->textInput(); ?>
  </div>
</div>

<div class="row">
  <div class="col-xs-3">
      <?= $form->field($formModel, 'popular')->checkbox(); ?>
  </div>

  <div class="col-xs-3">
      <?= $form->field($formModel, 'is_new')->checkbox(); ?>
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
