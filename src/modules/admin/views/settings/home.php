<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 * @var boolean $isCreate
 * @var \yii\widgets\ActiveForm $form
 * @var \ozerich\shop\modules\admin\forms\settings\HomeSettingsForm $formModel
 */
$this->title = 'Настройки главной страницы';
?>

<div class="row">

  <div class="col-sm-6">
      <?php $form = \yii\widgets\ActiveForm::begin([
          'enableClientValidation' => false,
      ]); ?>

      <?php ozerich\admin\widgets\FormPage::begin([
          'boxTitle' => 'SEO настройки',
          'isCreate' => $isCreate
      ]); ?>

    <div class="col-xs-12">
        <?= $form->field($formModel, 'page_title')->textInput() ?>
    </div>

    <div class="col-xs-12">
        <?= $form->field($formModel, 'meta_description')->textarea() ?>
    </div>

    <div class="col-xs-12">
        <?= $form->field($formModel, 'meta_image_id')->widget(ozerich\shop\modules\admin\widgets\ImageWidget::class, [
            'scenario' => 'og'
        ]); ?>
    </div>

    <div class="col-xs-12">
        <?= $form->field($formModel, 'content')->widget(\ozerich\shop\modules\admin\widgets\TinyMceWidget::class, [
            'options' => [
                'rows' => 30
            ]
        ]); ?>
    </div>

      <?php
      ozerich\admin\widgets\FormPage::end();
      \yii\widgets\ActiveForm::end();
      ?>
  </div>
  <div class="col-sm-6">

    <form action="/admin/settings/home-categories" method="post">
        <? $items = (new \ozerich\shop\services\categories\CategoriesService())->getTreeAsArray(); ?>
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Разделы каталога на главной странице</h3>
        </div>
        <div class="box-body">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Название</th>
              <th>Отображать на главной</th>
              <th>Позиция</th>
            </tr>
            </thead>
            <tbody>
            <? foreach ($items as $item): ?>
              <tr>
                <td>
                    <?= $item['plain_label'] ?>
                </td>
                <td>
                  <input type="checkbox" class="js-home-display"
                         name="home_display[<?= $item['model']->id ?>]" <?= $item['model']->home_display ? 'checked' : '' ?>>
                </td>
                <td>
                  <div class="position-wrapper" style="display: <?=$item['model']->home_display ? 'block' : 'none'?>">
                    <input type="number" name="home_position[<?= $item['model']->id ?>]" class="form-control"
                           value="<?= $item['model']->home_position ?>">
                  </div>
                </td>
              </tr>
            <? endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="box-footer">
          <button class="btn btn-primary">Сохранить</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  $(function(){
    $('.js-home-display').on('change', function(){
      $(this).parents('tr').find('.position-wrapper').toggle($(this).is(':checked')).find('input').get(0).focus();
    });
  });
</script>