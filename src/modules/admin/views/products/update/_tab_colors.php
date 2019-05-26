<?
/** @var ozerich\shop\models\Product $model */
?>

<form action="/admin/products/<?=$model->id?>/save-colors" method="post">

  <table class="table table-bordered">
    <thead>
    <tr>
      <th>Картинка</th>
      <th>Описание</th>
      <th>Цвет</th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($model->productImages as $productImage): ?>
      <tr>
        <td style="width: 200px; text-align: center"><img style="max-width: 200px;"
                                                          src="<?= $productImage->image->getUrl() ?>"></td>
        <td><textarea name="text[<?=$productImage->id?>]" style="width: 100%; height: 150px; resize: none"
                      class="form-control"><?= $productImage->text ?></textarea></td>
        <td>
          <select name="color[<?=$productImage->id?>]" class="form-control">
            <option value="">Не выбран</option>
              <? foreach (\ozerich\shop\models\Color::find()->all() as $color): ?>
                <option <?= $color->id == $productImage->color_id ? 'selected' : '' ?>
                    value="<?= $color->id ?>"><?= $color->name ?></option>
              <? endforeach; ?>
          </select>
        </td>
      </tr>
    <? endforeach; ?>
    </tbody>
  </table>

    <?= $this->render('_box_footer'); ?>
</form>