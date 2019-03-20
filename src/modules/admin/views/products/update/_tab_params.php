<?
/**
 * @var ozerich\shop\models\Product $model
 * @var ozerich\shop\structures\ProductField[] $fields
 */
?>

<form action="/admin/products/update-params/<?= $model->id ?>" method="post">
  <div class="row">
      <? foreach ($fields as $category_id => $category_fields): ?>
          <? if (count($fields) > 1): $cat = \ozerich\shop\models\Category::findOne($category_id); ?>
          <div class="col-xs-12">
            <h3><?= $cat->name ?></h3>
            <hr>
            <br />
          </div>
          <? endif; ?>
          <? foreach ($category_fields as $field): ?>
          <div class="col-xs-12">
            <div class="form-group">
              <label class="control-label" for="updateproductform-url_alias">
                  <?= $field->getField()->name ?> <?= $field->getField()->group ? ' (' . $field->getField()->group->name . ')' : '' ?>
              </label><br />
                <? if ($field->getField()->type == ozerich\shop\constants\FieldType::STRING): ?>
                  <input type="text" name="fields[<?= $field->getField()->id ?>]" class="form-control"
                         value="<?= $field->getValue() ?>">
                <? elseif ($field->getField()->type == ozerich\shop\constants\FieldType::INTEGER): ?>
                  <input type="number" name="fields[<?= $field->getField()->id ?>]" class="form-control"
                         value="<?= $field->getValue() ?>">
                <? elseif ($field->getField()->type == ozerich\shop\constants\FieldType::BOOLEAN): ?>
                  <input type="checkbox"
                         name="fields[<?= $field->getField()->id ?>]" <?= $field->getValue() ? 'checked' : '' ?>/>
                <? elseif ($field->getField()->type == ozerich\shop\constants\FieldType::SELECT):
                    $values = $field->getField()->values; ?>
                  <select class="form-control" name="fields[<?= $field->getField()->id ?>]">
                      <? foreach ($values as $value): ?>
                        <option
                            value="<?= $value ?>" <?= $field->getValue() == $value ? 'selected' : '' ?>><?= $value ?></option>
                      <? endforeach; ?>
                  </select>
                <? endif; ?>
            </div>
          </div>
          <? endforeach; ?>
      <? endforeach; ?>
  </div>

    <?= $this->render('_box_footer'); ?>
</form>