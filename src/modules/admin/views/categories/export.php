<?
$this->title = 'Экспорт товаров';

use yii\widgets\ActiveForm; ?>
<div class="box box-primary">
    <div class="box-body">
        <div class="row">
            <?php $form = ActiveForm::begin(); ?>
            <div class="col-xs-5">
                <?= $form->field($model, 'category_id')
                    ->widget(\ozerich\shop\modules\admin\widgets\CategoryWidget::class, [
                    'onlyCatalog' => true
                ]) ?>
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
    $('button').on('click', e => {
      e.preventDefault;
      let id = $('#dynamicmodel-category_id').val();
      window.open(`/api/category/export/${id}`, '_blank');
    })
</script>
