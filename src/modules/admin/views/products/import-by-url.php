<? /**
 * @var \yii\web\View $this
 * @var string $domains
 * @var string $error
 * @var \ozerich\shop\import\ImportProductAdminForm $formModel
 */
$this->title = 'Импорт товара по URL';
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false,
    'enableAjaxValidation' => true
]); ?>

  <p>Поддерживаются домены: <?= implode(', ', $domains) ?></p>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => true
]); ?>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'url')->textInput(); ?>
  </div>


  <div class="col-xs-12">
      <?= $form->field($formModel, 'category_id')->widget(\ozerich\shop\modules\admin\widgets\CategoryWidget::class, [
          'placeholder' => true,
          'multiple' => false
      ]) ?>
  </div>

<? if ($error): ?>
  <div class="col-xs-12">
    <span style="color: red;font-size: 24px; display: block; margin: 5px 0"><?= $error ?></span>
  </div>
<? endif; ?>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>