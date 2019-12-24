<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var ActiveForm $form
 * @var ozerich\shop\models\Page $formModel
 */
$this->title = $model->isNewRecord ? 'Добавить баннер' : 'Редактировать баннер';

use ozerich\shop\models\BannerAreas;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

if($model->isNewRecord){
    $model->area_id = Yii::$app->request->get('area');
}
?>

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
      <?= $form->field($formModel, 'area_id')->dropDownList(
              ArrayHelper::map(BannerAreas::find()->all(), 'id', 'name'), []
      ) ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'photo_id')->widget(ozerich\shop\modules\admin\widgets\ImageWidget::class) ?>
  </div>
  <div class="col-xs-12">
      <?= $form->field($formModel, 'title')->textInput() ?>
  </div>
  <div class="col-xs-12">
      <?= $form->field($formModel, 'text')->textarea() ?>
  </div>
  <div class="col-xs-12">
      <?= $form->field($formModel, 'url')->textInput() ?>
  </div>

<?php
ozerich\admin\widgets\FormPage::end();
ActiveForm::end();
?>
