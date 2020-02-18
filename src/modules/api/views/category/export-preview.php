
<?php

use ozerich\shop\constants\DiscountType;
use ozerich\shop\constants\Stock;

$array = [];
$productsIds = [];
$params = [];
$paramCount = [];


foreach ($products as $product) {
    $_productPrices = $product->prices;
    if(count($_productPrices)) {
        foreach ($_productPrices as $_productPrice) {
            $param = $_productPrice->paramValue;
            $paramSecond = $_productPrice->paramSecondValue;
            if($param && $param->productPriceParam && !array_key_exists($param->productPriceParam->name, $params)) {
                $params[$param->productPriceParam->name] = $param->productPriceParam;
                $count = array_key_exists($param->productPriceParam->name, $paramCount) ?
                    $paramCount[$param->productPriceParam->name] : null;
                $paramCount[$param->productPriceParam->name] = $count ? ++$count : 1;
            }
            if($paramSecond && $paramSecond->productPriceParam && !array_key_exists($paramSecond->name, $params)) {
                $params[$paramSecond->productPriceParam->name] = $paramSecond->productPriceParam;
                $count = array_key_exists($paramSecond->productPriceParam->name, $paramCount) ?
                    $paramCount[$paramSecond->productPriceParam->name] : null;
                $paramCount[$paramSecond->productPriceParam->name] = $count ? ++$count : 1;
            }
            if(($without_price == 'true') && $_productPrice->value > 0) {
                continue;
            }
            $array[] = $product->id;
        }
    } else {
        $array[] = $product->id;
    }
}
?>
<div class="counters">
    <div>Кол-во товаров: <b><?= count($products) ?></b></div>
    <div>Кол-во позиций: <b><?= count($array) ?></b></div>
</div>
<?php if(count($params)) { ?>
<div class="params">
    <h4>Ценовые параметры </h4>
    <table>
        <tr>
            <th>Название</th>
            <th>Кол-во позиций</th>
            <th>Позиция</th>
        </tr>
    <?php foreach ($params as $item) { ?>
        <tr class="row__param" data-param-name="<?= $item->name ?>">
            <td><?= $item->name ?></td>
            <td><?= $paramCount[$item->name] ?></td>
            <td>
                <a onclick="moveUp(this)" class="toTop">Выше</a>
                <a onclick="moveDown(this)" class="toBottom">Ниже</a>
            </td>
        </tr>
    <?php } ?>
    </table>
</div>
<?php } ?>
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
    tr.row__param a{
        cursor: pointer;
    }
    th{
        min-width: 200px;
    }
    td, th{
        padding: 5px 10px;
    }
    tr:nth-child(2n+1) {
        background: #f9f9f9;
    }
</style>

<script>
  function moveUp(el) {
    const row = $(el).parents("tr.row__param:first");
    row.insertBefore(row.prev('tr.row__param'));
    hideButtons();
  }
  function moveDown(el) {
    const row = $(el).parents("tr.row__param:first");
    row.insertAfter(row.next('tr.row__param'));
    hideButtons();
  }

  hideButtons();
  function hideButtons() {
    $('.row__param a').removeClass('hidden')
    $('.row__param a.toTop').eq(0).addClass('hidden')
    $('.row__param a.toBottom').last().addClass('hidden')
  }
</script>
