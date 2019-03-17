<?
/**
 * @var \app\models\Product $model
 * @var \app\structures\ProductField[] $fields
 */
?>

<form action="/admin/products/update-params/<?= $model->id ?>" method="post">
  <div class="row">
      <? foreach ($fields as $field): ?>
        <div class="col-xs-12">
          <div class="form-group">
            <label class="control-label" for="updateproductform-url_alias">
                <?= $field->getField()->name ?> <?= $field->getField()->group ? ' (' . $field->getField()->group->name . ')' : '' ?>
            </label><br />
              <? if ($field->getField()->type == \app\constants\FieldType::STRING): ?>
                <input type="text" name="fields[<?= $field->getField()->id ?>]" class="form-control"
                       value="<?= $field->getValue() ?>">
              <? elseif ($field->getField()->type == \app\constants\FieldType::INTEGER): ?>
                <input type="number" name="fields[<?= $field->getField()->id ?>]" class="form-control"
                       value="<?= $field->getValue() ?>">
              <? elseif ($field->getField()->type == \app\constants\FieldType::BOOLEAN): ?>
                <input type="checkbox"
                       name="fields[<?= $field->getField()->id ?>]" <?= $field->getValue() ? 'checked' : '' ?>/>
              <? elseif ($field->getField()->type == \app\constants\FieldType::SELECT):
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
  </div>

    <?= $this->render('_box_footer'); ?>
</form>