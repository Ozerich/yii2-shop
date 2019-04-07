<? /** @var \ozerich\shop\models\Category $model */ ?>

<? $service = new \ozerich\shop\services\categories\CategoriesService();

$categories = $service->getCategoriesForSameRoot($model);
?>

<form action="/admin/categories/appearance/<?= $model->id ?>" method="post">
  <table class="table">
    <thead>
    <tr>
      <th>Категория</th>
      <th>Ссылка</th>
      <th>Отображать</th>
      <th>Позиция</th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($categories as $category):
        $exist = \ozerich\shop\models\CategoryDisplay::find()
            ->andWhere('parent_id=:parent_id', [':parent_id' => $model->id])
            ->andWhere('category_id=:category_id', [':category_id' => $category->id])
            ->one();
        ?>
      <tr>
        <td style="vertical-align: middle">
            <?= $category->name ?><br />
          <a href="/admin/categories/update?id=<?= $category->id ?>" target="_blank">Редактировать</a>
        </td>
        <td style="vertical-align: middle">
          <a href="<?= $category->getUrl(true) ?>" target="_blank"><?= $category->getUrl(); ?></a>
        </td>
        <td style="vertical-align: middle"><input type="checkbox"
                                                  class="js-view-checkbox" <?= $exist ? 'checked' : '' ?>
                                                  name="display[<?= $category->id ?>]"></td>
        <td><input type="number" class="form-control js-position-input" value="<?= $exist ? $exist->position : '' ?>"
                   name="position[<?= $category->id ?>]"
                   style="width: 100px; text-align: center; display: <?= $exist ? 'block' : 'none' ?>"></td>
      </tr>
    <? endforeach; ?>
    </tbody>
  </table>
  <div class="box-footer">
    <div class="pull-right">
      <input type="submit" value="Cохранить" class="btn btn-primary">
    </div>
  </div>
</form>

<script>
  $(function () {
    $('.js-view-checkbox').on('change', function () {
      $(this).parents('tr').find('.js-position-input').toggle($(this).is(':checked'));
    });
  });

</script>