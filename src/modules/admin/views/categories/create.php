<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var ozerich\shop\modules\admin\forms\CategoryForm $formModel
 * @var ozerich\shop\models\Category $model
 */
$this->title = $model->isNewRecord ? 'Создать категорию' : 'Редактировать категорию';
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => $isCreate
]); ?>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'type')->dropDownList(\ozerich\shop\constants\CategoryType::getList()); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'name')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'parent_id')->widget(\ozerich\shop\modules\admin\widgets\CategoryWidget::class, [
          'allowEmptyValue' => true
      ]) ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'url_alias')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'image_id')->widget(ozerich\shop\modules\admin\widgets\ImageWidget::class, [
          'scenario' => 'category'
      ]); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'text')->widget(\ozerich\shop\modules\admin\widgets\TinyMceWidget::class); ?>
  </div>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>