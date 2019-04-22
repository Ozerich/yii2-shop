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

$this->title = $model->isNewRecord ? 'Создать пост' : 'Редактировать пост';

$result = ['' => 'Без категории'];

$tree = (new \ozerich\shop\services\blog\BlogService())->getTreeAsArray();
foreach ($tree as $id => $item) {
    $result[$item['model']['id']] = $item['plain_label'];
}

?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false
]); ?>

<? ozerich\admin\widgets\FormPage::begin([
    'isCreate' => $isCreate
]); ?>


  <div class="col-xs-12">
      <?= $form->field($formModel, 'category_id')->dropDownList($result); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'title')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'url_alias')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'image_id')->widget(\ozerich\shop\modules\admin\widgets\ImageWidget::class, [
          'scenario' => 'blog'
      ]); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'excerpt')->textarea(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'content')->widget(\ozerich\admin\widgets\TinyMce::class, [
          'options' => [
              'rows' => 30
          ]
      ]); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'page_title')->textInput(); ?>
  </div>

  <div class="col-xs-12">
      <?= $form->field($formModel, 'meta_description')->textarea(); ?>
  </div>

<?php
ozerich\admin\widgets\FormPage::end();
\yii\widgets\ActiveForm::end();
?>