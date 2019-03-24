<? /** @var \ozerich\shop\models\Product $model */ ?>
<? if ($model->is_prices_extended): ?>-<? else: ?>
  <input type="text" class="form-control js-price-input" style="width: 80px; text-align: center" value="<?= $model->price ?>" data-id="<?= $model->id ?>">
<? endif; ?>