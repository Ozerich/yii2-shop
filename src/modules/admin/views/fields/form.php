<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var \app\models\Field $formModel
 */
$this->title = $model->isNewRecord ? 'Создать поле' : 'Редактировать поле';

$formModel->values = $formModel->values ? implode("\n", $formModel->values) : '';
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => $isCreate
]); ?>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'name')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'group_id')->dropDownList(
          \yii\helpers\ArrayHelper::map(\app\models\FieldGroup::find()->all(), 'id', 'name'), [
              'prompt' => 'Без группы'
          ]
      ); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'type')->dropDownList(\app\constants\FieldType::getList()); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'image_id')->widget(\app\modules\admin\widgets\ImageWidget::class, [
          'scenario' => 'field'
      ]); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'values')->textarea([
          'style' => 'height: 200px;'
      ]); ?>
  </div>

  <div class="col-xs-6">
      <?= $form->field($formModel, 'value_prefix')->textInput(); ?>
  </div>

  <div class="col-xs-6">
      <?= $form->field($formModel, 'value_suffix')->textInput(); ?>
  </div>
<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>