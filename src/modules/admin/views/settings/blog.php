<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var \ozerich\shop\modules\admin\forms\settings\BlogSettingsForm $formModel
 */
$this->title = 'Настройки блога';
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false,
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => $isCreate
]); ?>

<div class="col-xs-12">
    <?= $form->field($formModel, 'enabled')->checkbox() ?>
</div>
<div class="col-xs-12">
    <?= $form->field($formModel, 'page_title')->textInput() ?>
</div>

<div class="col-xs-12">
    <?= $form->field($formModel, 'meta_description')->textarea() ?>
</div>

<div class="col-xs-12">
    <?= $form->field($formModel, 'meta_image_id')->widget(ozerich\shop\modules\admin\widgets\ImageWidget::class, [
        'scenario' => 'og'
    ]); ?>
</div>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>

