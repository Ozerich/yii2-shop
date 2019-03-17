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
    'enableClientValidation' => false,
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => $isCreate
]); ?>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'parent_id')->dropDownList(
          \yii\helpers\ArrayHelper::map(ozerich\shop\models\Category::findRoot()->all(), 'id', 'name'),
          ['prompt' => 'Без родительской категории']
      ); ?>
  </div>
  <div class="col-xs-12">
      <?= $form->field($formModel, 'name')->textInput(); ?>
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
      <?= $form->field($formModel, 'field_ids')->checkboxList(
          \yii\helpers\ArrayHelper::map(ozerich\shop\models\Field::find()->all(), 'id', 'name')
      ) ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'text')->widget(\ozerich\admin\widgets\TinyMce::class); ?>
  </div>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>