<?
$this->title = 'Экспорт товаров';

use ozerich\shop\models\Manufacture;
use ozerich\shop\modules\admin\widgets\CategoryWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<div class="box box-primary">
    <div class="box-body">
        <div class="row">
            <?php $form = ActiveForm::begin(); ?>
            <div class="col-xs-5">
                <?= $form->field($model, 'category_id')
                    ->widget(CategoryWidget::class, [
                    'onlyCatalog' => true
                ]) ?>
            </div>
            <div class="col-xs-5">
                <?= $form->field($model, 'manufacture_id')->dropDownList(
                      ['all' => 'Все'] +  ArrayHelper::map(Manufacture::find()->all(), 'id', 'name')
                ) ?>
            </div>
            <div class="col-xs-5">
                <?= Html::checkbox('without_price', false, ['label' => 'Без цены']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">Экспорт</button>
    </div>
</div>
<script>
    $('label[for=dynamicmodel-category_id]').html('Категория');
    $('label[for=dynamicmodel-manufacture_id]').html('Производитель');
    $('label[for=dynamicmodel-category_id]').html('Категория');
    $('button').on('click', e => {
      e.preventDefault;
      let id = $('#dynamicmodel-category_id').val();
      let mid = $('#dynamicmodel-manufacture_id').val();
      let price = $('input[name="without_price"]').prop('checked') ? true : false;
      window.open(`/api/category/export?id=${id}&manufacture_id=${mid}&without_price=${price}`, '_blank');
    })
</script>
