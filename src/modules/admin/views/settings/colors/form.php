<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var \ozerich\shop\modules\admin\forms\ColorForm $formModel
 */
$this->title = $model->isNewRecord ? 'Создать цвет' : 'Редактировать цвет';
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
    <?= $form->field($formModel, 'type')->dropDownList([
        'COLOR' => 'Цвет',
        'IMAGE' => 'Картинка',
    ], ['prompt' => 'Выберите тип']); ?>
</div>

<div class="col-xs-12 js-color-block" style="display: <?= $formModel->type == 'COLOR' ? 'block' : 'none' ?>">
    <?= $form->field($formModel, 'color')->input('color'); ?>
</div>

<div class="col-xs-12 js-image-block" style="display: <?= $formModel->type == 'IMAGE' ? 'block' : 'none' ?>">
    <?= $form->field($formModel, 'image_id')->widget(\ozerich\shop\modules\admin\widgets\ImageWidget::class); ?>
</div>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>

<script>
  $('#colorform-type').on('change', function () {
    $('.js-color-block').toggle($(this).val() === 'COLOR');
    $('.js-image-block').toggle($(this).val() === 'IMAGE');
  });
</script>
