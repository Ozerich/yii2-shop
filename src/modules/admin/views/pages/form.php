<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var ozerich\shop\models\Page $formModel
 */
$this->title = $model->isNewRecord ? 'Создать категорию' : 'Редактировать категорию';
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false,
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => $isCreate
]); ?>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'title')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'url')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'content')->widget(\ozerich\admin\widgets\TinyMce::class); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'meta_title')->textInput() ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'meta_description')->textarea() ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'meta_image_id')->widget(ozerich\shop\modules\admin\widgets\ImageWidget::class, [
          'scenario' => 'category'
      ]); ?>
  </div>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>