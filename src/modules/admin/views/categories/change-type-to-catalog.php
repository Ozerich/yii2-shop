<? /**
 * @var \yii\web\View $this
 * @var ozerich\shop\models\Category $model
 */
$this->title = 'Изменить тип категории "' . $model->name . '" на "Каталог"';
?>

<form action="#" method="post">
  <div class="box">
    <div class="box-body">
      <p>Подтвердите пожалуйста</p>
    </div>
    <div class="box-footer">
      <a href="#" class="btn btn-danger">Отменить</a>
      <button class="btn btn-success" type="submit">Подвердить</button>
    </div>
  </div>
</form>
