<?php $form = \yii\widgets\ActiveForm::begin([
    'enableClientValidation' => false,
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]); ?>

  <div class="row">
    <div class="col-xs-12">
        <?= $form->field($formModel, 'name')->textInput(); ?>
    </div>

    <div class="col-xs-12">
        <?= $form->field($formModel, 'category_id')->dropDownList(
            \yii\helpers\ArrayHelper::map(ozerich\shop\models\Category::getTree(), 'id', 'name')
        ); ?>
    </div>

    <div class="col-xs-12">
        <?= $form->field($formModel, 'url_alias')->textInput(); ?>
    </div>

    <div class="col-xs-12">
        <?= $form->field($formModel, 'image_id')->widget(ozerich\shop\modules\admin\widgets\ImageWidget::class, [
            'scenario' => 'product'
        ]); ?>
    </div>

    <div class="col-xs-3">
        <?= $form->field($formModel, 'price')->textInput(['type' => 'number']); ?>
    </div>

    <div class="col-xs-3">
      <div style="margin-top: 30px;">
          <?= $form->field($formModel, 'is_prices_extended')->checkbox(); ?>
      </div>
    </div>

    <div class="col-xs-3">
      <div style="margin-top: 30px;">
          <?= $form->field($formModel, 'popular')->checkbox(); ?>
      </div>
    </div>


    <div class="col-xs-12">
        <?= $form->field($formModel, 'text')->widget(\ozerich\admin\widgets\TinyMce::class); ?>
    </div>

  </div>

<?= $this->render('_box_footer'); ?>

<?php
\yii\widgets\ActiveForm::end();
?>