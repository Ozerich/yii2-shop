<?
/** @var ozerich\shop\models\Product $model */
/** @var \ozerich\shop\modules\admin\forms\ProductConnectionsForm $formModel */
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'action' => '/admin/products/' . $model->id . '/save-connections',
    'enableClientValidation' => true,
]); ?>

  <div class="row">

    <div class="col-xs-12">
        <?= $form->field($formModel, 'category_id')->widget(\ozerich\shop\modules\admin\widgets\CategoryWidget::class, [
            'multiple' => false
        ]); ?>
    </div>

    <div class="col-xs-6">
        <?= $form->field($formModel, 'manufacture_id')->dropDownList(\ozerich\shop\models\Manufacture::getList(), [
            'prompt' => 'Отсуствует'
        ]); ?>
    </div>

    <div class="col-xs-6">
        <?= $form->field($formModel, 'collection_id')->dropDownList(\ozerich\shop\models\ProductCollection::getList(), [
            'prompt' => 'Отсуствует'
        ]); ?>
    </div>

    <div class="col-xs-12">
        <?= $form->field($formModel, 'same')->widget(\ozerich\shop\modules\admin\widgets\ProductsSameSelect2Widget::class, [
            'excludeId' => $model->id,
        ]); ?>
    </div>
    <div class="col-xs-12">
        <table class="same-products-table">
            <tr>
                <th style="width: 350px">Название</th>
                <th style="width: 150px">Двусторонняя связь</th>
                <th style="width: 300px">Позиция</th>
            </tr>
            <tr id="same-products-row__template" style="display: none" data-product-id="">
                <td class="same-products-cell__name"></td>
                <td class="same-products-cell__two-side">
                    <input type="checkbox">
                </td>
                <td>
                    <a onclick="moveUp(this)" class="toTop">Выше</a>
                    <a onclick="moveDown(this)" class="toBottom">Ниже</a>
                </td>
            </tr>
            <?php foreach ($formModel->same as $item) { ?>
                <tr class="same-products-row" data-product-id="<?= $item->id ?>">
                    <td><?= $item->name ?></td>
                    <td>
                        <input onclick="addTwoSideRelation(this)" type="checkbox" class="same-products__checkbox"
                            <?= $item->isSameProduct($model) ? 'checked' : '' ?>>
                    </td>
                    <td>
                        <a onclick="moveUp(this)" class="toTop">Выше</a>
                        <a onclick="moveDown(this)" class="toBottom">Ниже</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
            <input type="hidden" name="ProductConnectionsForm[priority]" value="">
            <input type="hidden" name="ProductConnectionsForm[two_side]" value="">
    </div>
  </div>
<script>
    let sameProducts = [], sameProductsPriority = [], sameProductsTwoSide = [];

    $('.same-products-row').each(function () {
      if($(this).attr("data-product-id")) {
        sameProducts.push($(this).attr('data-product-id'));
        sameProductsPriority.push($(this).attr('data-product-id'));
        if ($(this).find('.same-products__checkbox').is(':checked')) {
          sameProductsTwoSide.push($(this).attr('data-product-id'));
        }
      }
      controlSameProductValues();
    });

    $('#sameProducts').on('change', function () {
      const vals = $("#sameProducts").select2("data");
      for(let i = 0; i  < vals.length ; i++){
        if(sameProducts.filter(u => u == vals[i].id).length == 0) {
          addNewProductSameRow({
            id: vals[i].id,
            name: vals[i].text
          })
        }
      }
      for(let i = 0; i  < sameProducts.length ; i++){
        if(vals.filter(u => u.id == sameProducts[i]).length == 0) {
          removeProductSameRow(sameProducts[i]);
          sameProductsPriority = sameProductsPriority.filter(u => u !== sameProducts[i]);
          sameProductsTwoSide = sameProductsTwoSide.filter(u => u !== sameProducts[i]);
        }
      }
      sameProducts = vals.map(u => u.id);
      controlSameProductValues();
    })

    function addTwoSideRelation(el) {
      const id = $(el).parents('.same-products-row').attr('data-product-id');
      if(sameProductsTwoSide.filter(u => u == id).length == 0) {
        sameProductsTwoSide.push(id);
      } else {
        sameProductsTwoSide = sameProductsTwoSide.filter(u => u !== id);
      }
      controlSameProductValues();
    }

    function addNewProductSameRow(obj) {
      sameProducts.push(obj.id)
      sameProductsPriority.push(obj.id);
      let newRow = $('#same-products-row__template').clone();
      newRow.addClass('same-products-row');
      newRow.removeAttr('id');
      newRow.attr("data-product-id", obj.id);
      newRow.find(".same-products-cell__name").html(obj.name);
      newRow.appendTo('.same-products-table');
      newRow.show();
      newRow.find('.same-products__checkbox').on('click', function () {
        addTwoSideRelation(this);
      });
    }

    function removeProductSameRow(id) {
      $(`.same-products-row[data-product-id="${id}"]`).remove();
    }

    function controlSameProductValues() {
      $('input[name="ProductConnectionsForm[priority]"]').val(sameProductsPriority);
      $('input[name="ProductConnectionsForm[two_side]"]').val(sameProductsTwoSide);
      hideButtons();
    }

    function changePriority() {
      sameProductsPriority = [];
        $('.same-products-row').each(function () {
          sameProductsPriority.push($(this).attr('data-product-id'))
        });
        console.log(sameProductsPriority);
      controlSameProductValues();
    }

  function moveUp(el) {
    const row = $(el).parents("tr.same-products-row:first");
    row.insertBefore(row.prev('tr.same-products-row'));
    changePriority();
    hideButtons();
  }
  function moveDown(el) {
    const row = $(el).parents("tr.same-products-row:first");
    row.insertAfter(row.next('tr.same-products-row'));
    changePriority();
    hideButtons();
  }

  hideButtons();
  function hideButtons() {
    $('.same-products-row a').removeClass('hidden')
    $('.same-products-row a.toTop').eq(0).addClass('hidden')
    $('.same-products-row a.toBottom').last().addClass('hidden')
  }
</script>
<?= $this->render('_box_footer'); ?>

<?php \yii\widgets\ActiveForm::end(); ?>
