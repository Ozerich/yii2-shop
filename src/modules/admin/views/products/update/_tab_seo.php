<?php
/**
 * @var ozerich\shop\models\Product $model
 * @var \ozerich\shop\modules\admin\forms\ProductSeoForm $formModel
 */
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'action' => '/admin/products/' . $model->id . '/save-seo',
    'enableClientValidation' => true
]); ?>

  <div class="row">
    <div class="col-xs-12">
        <?= $form->field($formModel, 'h1_value')->textInput(); ?>

    </div>

    <div class="col-xs-12">
        <?= $form->field($formModel, 'seo_title')->textInput(); ?>
    </div>

    <div class="col-xs-12">
        <?= $form->field($formModel, 'seo_description')->textarea(); ?>
    </div>
  </div>

<?= $this->render('_box_footer'); ?>

<?php \yii\widgets\ActiveForm::end(); ?>