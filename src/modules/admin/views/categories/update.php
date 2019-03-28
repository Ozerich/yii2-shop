<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var ozerich\shop\modules\admin\forms\CategoryForm $formModel
 * @var ozerich\shop\models\Category $model
 * @var \ozerich\shop\modules\admin\forms\CategorySeoForm $seoFormModel
 */
$this->title = 'Редактировать категорию';
?>

<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#main" data-toggle="tab">Основные параметры</a></li>
    <li><a href="#params" data-toggle="tab">Товарные поля</a></li>
    <li><a href="#seo" data-toggle="tab">SEO параметры</a></li>
  </ul>
  <div class="tab-content">
    <div class="active tab-pane" id="main">
        <?php $form = \yii\widgets\ActiveForm::begin([
            'enableClientValidation' => false
        ]); ?>

      <div class="row">
        <div class="col-xs-12">
            <?= $form->field($formModel, 'parent_id')->widget(\ozerich\shop\modules\admin\widgets\CategoryWidget::class, [
                'allowEmptyValue' => true
            ]) ?>
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
            <?= $form->field($formModel, 'text')->widget(\ozerich\admin\widgets\TinyMce::class); ?>
        </div>
      </div>

      <div class="box-footer">
        <div class="pull-right">
          <input type="submit" value="Cохранить" class="btn btn-primary" name="only-save">
        </div>
      </div>

        <?php \yii\widgets\ActiveForm::end(); ?>

    </div>
    <div class="tab-pane" id="params">
        <? /** @var \yii\web\View $this */ ?>
        <? ozerich\shop\modules\admin\react\CategoryFieldsAsset::register($this); ?>
      <div id="react-app-category-fields" data-category-id="<?= $model->id ?>"></div>
    </div>
    <div class="tab-pane" id="seo">
        <?php $form = \yii\widgets\ActiveForm::begin([
            'action' => '/admin/categories/' . $model->id . '/save-seo',
            'enableClientValidation' => true
        ]); ?>

      <div class="row">
        <div class="col-xs-12">
            <?= $form->field($seoFormModel, 'h1_value')->textInput(); ?>

        </div>

        <div class="col-xs-12">
            <?= $form->field($seoFormModel, 'seo_title')->textInput(); ?>
        </div>

        <div class="col-xs-12">
            <?= $form->field($seoFormModel, 'seo_description')->textarea(); ?>
        </div>
      </div>

      <div class="box-footer">
        <div class="pull-right">
          <input type="submit" value="Cохранить" class="btn btn-primary">
        </div>
      </div>

        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
  </div>
</div>
