<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var \ozerich\shop\modules\admin\forms\settings\SeoSettingsForm $formModel
 */
$this->title = 'Настройки SEO';
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false,
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => $isCreate
]); ?>

<div class="col-xs-12">
    <?= $form->field($formModel, 'products_title_template')->textInput() ?>
</div>

<div class="col-xs-12">
    <?= $form->field($formModel, 'products_description_template')->textarea() ?>
</div>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>

