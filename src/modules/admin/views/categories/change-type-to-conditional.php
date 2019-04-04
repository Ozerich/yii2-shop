<? /**
 * @var \yii\web\View $this
 * @var ozerich\shop\models\Category $model
 * @var \ozerich\shop\modules\admin\forms\CategoryChangeTypeToConditionalForm $formModel
 */
$this->title = 'Изменить тип категории "' . $model->name . '" на "Условная категория"';
?>

<? $form = \yii\widgets\ActiveForm::begin([]); ?>

<div class="box">
  <div class="box-body">
      <?= $form->field($formModel, 'category_id')->widget(\ozerich\shop\modules\admin\widgets\CategoryWidget::class, [
          'onlyCatalog' => true,
          'exclude' => $model->id
      ]); ?>
  </div>
  <div class="box-footer">
    <a href="#" class="btn btn-danger">Отменить</a>
    <button class="btn btn-success" type="submit">Подвердить</button>
  </div>
</div>

<? \yii\widgets\ActiveForm::end() ?>
