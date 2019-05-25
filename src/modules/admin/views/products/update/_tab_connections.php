<?
/** @var ozerich\shop\models\Product $model */
/** @var \ozerich\shop\modules\admin\forms\ProductConnectionsForm $formModel */
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'action' => '/admin/products/' . $model->id . '/save-connections',
    'enableClientValidation' => true,
]); ?>

  <div class="row">

    <div class="col-xs-12">
        <?= $form->field($formModel, 'category_id')->widget(\ozerich\shop\modules\admin\widgets\CategoryWidget::class, [
            'multiple' => false
        ]); ?>
    </div>

    <div class="col-xs-6">
        <?= $form->field($formModel, 'manufacture_id')->dropDownList(\ozerich\shop\models\Manufacture::getList(), [
            'prompt' => 'Отсуствует'
        ]); ?>
    </div>

    <div class="col-xs-6">
        <?= $form->field($formModel, 'collection_id')->dropDownList(\ozerich\shop\models\ProductCollection::getList(), [
            'prompt' => 'Отсуствует'
        ]); ?>
    </div>

    <div class="col-xs-12">
        <?= $form->field($formModel, 'same')->widget(\ozerich\shop\modules\admin\widgets\ProductsSelect2Widget::class, [
            'excludeId' => $model->id
        ]); ?>
    </div>
  </div>

<?= $this->render('_box_footer'); ?>

<?php \yii\widgets\ActiveForm::end(); ?>