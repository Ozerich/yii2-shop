<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var \app\models\Product $model
 * @var \app\modules\admin\forms\CreateProductForm $formModel
 * @var \app\structures\ProductField[] $fields
 */
$this->title = 'Создать товар';
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false,
    'options' => [
        'enctype' => 'multipart/form-data'
    ],
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => true
]); ?>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'name')->textInput(); ?>
  </div>

<? if ($model->isNewRecord): ?>
  <div class="col-xs-12">
      <?= $form->field($formModel, 'category_id')->dropDownList(
          \yii\helpers\ArrayHelper::map(\app\models\Category::getTree(), 'id', 'name')
      ); ?>
  </div>
<? endif; ?>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'image_id')->widget(\app\modules\admin\widgets\ImageWidget::class, [
          'scenario' => 'product'
      ]); ?>
  </div>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>