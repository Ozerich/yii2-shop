
<?php

use ozerich\shop\constants\DiscountType;
use ozerich\shop\constants\Stock;

$array = [];
$productsIds = [];
$param_name_second = null;
$param_name = null;
foreach ($products as $product) {
    $_productPrices = $product->prices;
    if(count($_productPrices)) {
        foreach ($_productPrices as $_productPrice) {
            $param = $_productPrice->paramValue;
            $paramSecond = $_productPrice->paramSecondValue;
            if(!$param_name || !$param_name_second){
                if($param) {
                    $name = $param->productPriceParam->name;
                    $param_name = $param_name ? $param_name : (
                    $param_name_second !== $name ? $name : null
                    );
                    $param_name_second = $param_name_second ? $param_name_second : (
                    $param_name !== $name ? $name : null
                    );
                } elseif($paramSecond){
                    $name = $param->productPriceParam->name;
                    $param_name = $param_name ? $param_name : (
                    $param_name_second !== $name ? $name : null
                    );
                    $param_name_second = $param_name_second ? $param_name_second : (
                    $param_name !== $name ? $name : null
                    );
                }
            }
            $modify = $weight = '';
            if($param && $param->productPriceParam->name == $param_name) {
                $modify = $param->name;
            } elseif($paramSecond && $paramSecond->productPriceParam->name == $param_name) {
                $modify =  $paramSecond->name;
            }
            if($param && $param->productPriceParam->name == $param_name_second) {
                $weight = $param->name;
            } elseif($paramSecond && $paramSecond->productPriceParam->name == $param_name_second) {
                $weight = $paramSecond->name;
            }
            if(($without_price == 'true') && $_productPrice->value > 0) {
                continue;
            }
            $first = false;
            if($param && $paramSecond) {
                if($param->productPriceParam->priority > $paramSecond->productPriceParam->priority){
                    $first = true;
                    $priorityNew = $param->productPriceParam->priority;
                } else {
                    $priorityNew = $paramSecond->productPriceParam->priority;
                }
            } else {
                $priorityNew = $param->productPriceParam->priority;
            }
            $array[] = [
                $product->id,
                $product->manufacture ? $product->manufacture->name : '',
                $product->name,
                $product->label,
                $modify,
                $weight,
                $_productPrice->value,
                $_productPrice->discount_mode == DiscountType::FIXED ? $_productPrice->discount_value : null,
                $_productPrice->discount_mode == DiscountType::PERCENT ? $_productPrice->discount_value : null,
                $_productPrice->discount_mode == DiscountType::AMOUNT ? $_productPrice->discount_value : null,
                $priority = !$first ? $param->priority : $paramSecond->priority,
                Stock::toLabel($_productPrice->stock), // J
                $_productPrice->stock_waiting_days, // K
                $_productPrice->comment, // L
                $_productPrice->id, // M
                ($product->id * ($_productPrice->id + 3) ), // N
                $product->popular_weight,
                $priorityNew,
                count($_productPrices)
            ];
        }
    } else {
        $array[] = [
            $product->id,
            $product->manufacture ? $product->manufacture->name : '',
            $product->name,
            $product->label,
            null,
            null,
            $product->price,
            $product->discount_mode == DiscountType::FIXED ? $product->discount_value : null,
            $product->discount_mode == DiscountType::PERCENT ? $product->discount_value : null,
            $product->discount_mode == DiscountType::AMOUNT ? $product->discount_value : null,
            $priority = 1,
            null, // J
            null, // K
            $product->price_comment, // L
            null, //M
            null, // N,
            $product->popular_weight,
            1,
            1
        ];
    }
    array_multisort(array_column($array, 16),  SORT_DESC,
        array_column($array, 0),  SORT_DESC,
        array_column($array, 10), SORT_ASC,
        array_column($array, 17), SORT_ASC,
        array_column($array, 4), SORT_ASC,
        $array);
}
?>
<div class="counters">
    <div>Кол-во товаров: <b><?= count($products) ?></b></div>
    <div>Кол-во позиций: <b><?= count($array) ?></b></div>
</div>
<table>
    <tr>
        <th>Название</th>
        <th>Кол-во</th>
        <th>Позиция</th>
    </tr>
    <?php foreach ($array as $item) {
        if($item[18] && !array_key_exists($item[0], $productsIds)) {
            $productsIds[$item[0]] = true;
            ?>
            <tr data-product-id="<?= $item[0] ?>" class="product_item">
                <td><?= $item[2] ?></td>
                <td><?= $item[18] ?></td>
                <td>
                    <a onclick="moveUp(this, <?= $item[15] ?? -1 ?>,<?= $item[0] ?>)">Вверх</a>
                    <a onclick="moveDown(this, <?= $item[15] ?? -1 ?>,<?= $item[0] ?>)">Вниз</a></td>
            </tr>
            <tr data-price-id="<?= $item[15] ?>" data-product-id="<?= $item[0] ?>" class="product_price">
                <td><?= $item[2] ?></td>
                <td><?= $item[4] ?> </td>
                <td><?= $item[5] ?></td>
            </tr>
        <?php } else { ?>
            <tr data-price-id="<?= $item[15] ?>" data-product-id="<?= $item[0] ?>" class="product_price">
                <td><?= $item[2] ?></td>
                <td><?= $item[4] ?> </td>
                <td><?= $item[5] ?></td>
            </tr>
        <?php }
     } ?>


</table>
<div class="row" style="margin: 30px 0 0">
    <div class="col-xs-12">
        <div class="form-group field-updateproductform-type">
            <label class="control-label" for="updateproductform-type">Название файла</label>
            <input id="filename" value="<?= $category->name ?>"/> <span>.xlsx</span>
        </div>
    </div>
</div>


<style>
    .counters{
        margin-bottom: 20px;
    }
    .product_item {
        background: #f4f4f4;
    }
    .product_item td{
        padding: 15px 10px;
    }
    .product_price td:first-child{
        font-size: 12px;
        padding-left: 15px;
    }
</style>

<script>
    function moveUp(el, priceId, productId) {
      const rows = $('tr[data-product-id="' + $(el).parents("tr.product_item").attr("data-product-id") + '"]');
      rows.each(function () {
        if( $(this).prevAll('tr.product_item').length > 0) {
          if($($(this).prevAll('tr.product_item')[0]).attr("data-product-id") !==
            $(this).attr("data-product-id"))
          $(this).insertBefore($(this).prevAll('tr.product_item')[0]);
        }
      })
    }
    function moveDown(el, priceId, productId) {
      const rows = $('tr[data-product-id="' + $(el).parents("tr.product_item").attr("data-product-id") + '"]');
      let nextPrice = $(el).parents("tr.product_item").nextAll('tr.product_item')[0];
      rows.insertAfter($('tr.product_price[data-product-id="'+$(nextPrice).attr('data-product-id')+'"]').last());
    }
</script>
