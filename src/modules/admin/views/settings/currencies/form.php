<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var ozerich\shop\models\Page $formModel
 */
$this->title = $model->isNewRecord ? 'Создать валюту' : 'Редактировать валюту';
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false,
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => $isCreate
]); ?>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'name')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'full_name')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'rate')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'primary')->checkbox(); ?>
  </div>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>