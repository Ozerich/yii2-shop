<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var ozerich\shop\models\Product $model
 * @var ozerich\shop\modules\admin\forms\CreateProductForm $formModel
 * @var ozerich\shop\structures\ProductField[] $fields
 */
$this->title = 'Создать товар';
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => true
]); ?>

  <div class="col-xs-9">
      <?= $form->field($formModel, 'name')->textInput(); ?>
  </div>

  <div class="col-xs-3">
      <?= $form->field($formModel, 'sku')->textInput(); ?>
  </div>

<? if ($model->isNewRecord): ?>
  <div class="col-xs-12">
      <?= $form->field($formModel, 'category_id')->widget(\ozerich\shop\modules\admin\widgets\CategoryWidget::class, [
          'placeholder' => true,
          'multiple' => true
      ]) ?>
  </div>
<? endif; ?>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'image_id')->widget(ozerich\shop\modules\admin\widgets\ImageWidget::class, [
          'scenario' => 'product'
      ]); ?>
  </div>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>