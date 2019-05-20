<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var ozerich\shop\models\ProductCollection $formModel
 */
$this->title = $model->isNewRecord ? 'Создать коллекцию' : 'Редактировать коллекцию';
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => $isCreate
]); ?>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'title')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'url_alias')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'manufacture_id')->dropDownList(\ozerich\shop\models\Manufacture::getList(), [
          'prompt' => 'Отсуствует'
      ]); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'image_id')->widget(ozerich\shop\modules\admin\widgets\ImageWidget::class, [
          'scenario' => 'collection'
      ]); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'content')->widget(\ozerich\admin\widgets\TinyMce::class); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'seo_title')->textInput() ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'seo_description')->textarea() ?>
  </div>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>