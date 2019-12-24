<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var ActiveForm $form
 * @var ozerich\shop\models\Page $formModel
 */
$this->title = $model->isNewRecord ? 'Создание новой зоны' : 'Редактирование зоны';

use yii\widgets\ActiveForm; ?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => $isCreate
]); ?>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'alias')->textInput() ?>
  </div>
  <div class="col-xs-12">
      <?= $form->field($formModel, 'name')->textInput() ?>
  </div>

<?php
ozerich\admin\widgets\FormPage::end();
ActiveForm::end();
?>
