<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var ozerich\shop\modules\admin\forms\CategoryForm $formModel
 * @var ozerich\shop\models\Category $model
 *
 * @var ozerich\shop\models\Menu $menu
 */

$this->title = $model->isNewRecord ? 'Создать пункт меню' : 'Редактировать пункт меню';

$parentsQuery = ozerich\shop\models\MenuItem::findByMenu($menu);
if (!$model->isNewRecord) {
    $parentsQuery->andWhere('id <> :id', [':id' => $model->id])->all();
}
$parents = $parentsQuery->all();
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false
]); ?>

<?php ozerich\admin\widgets\FormPage::begin([
    'isCreate' => $isCreate
]); ?>


  <div class="col-xs-12">
      <?= $form->field($formModel, 'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map(
          $parents, 'id', 'title'), [
          'prompt' => 'Нет родительской'
      ]); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'title')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'url')->textInput(); ?>
  </div>


<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>