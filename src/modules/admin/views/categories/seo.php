<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'SEO-теги у категорий';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'name' => [
            'header' => 'Название',
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function ($category) {
                $link = $category['model']->getUrl(true);
                $label = $category['model']->getUrl(false);

                if ($category['model']['type'] == \ozerich\shop\constants\CategoryType::CONDITIONAL) {
                    $name = $category['plain_label'] . '<i> - Условная категория</i>';
                } else {
                    $name = $category['plain_label'];
                }

                return $name . '<br/>' . \yii\helpers\Html::a($label, $link, ['target' => '_blank']);
            }
        ],
        [
            'header' => 'тег Title',
            'format' => 'raw',
            'value' => function ($category) {
                return '<div class="js-count" data-id="' . $category['model']->id . '" data-max="70" data-min="50"><textarea class="form-control js-title-val" style="min-width: 400px; height: 70px; resize: none">' . $category['model']->seo_title . '</textarea></div>';
            }
        ],
        [
            'header' => 'SEO Description',
            'format' => 'raw',
            'value' => function ($category) {
                return '<div class="js-count" data-id="' . $category['model']->id . '" data-max="170" data-min="150"><textarea class="form-control js-description-val" style="min-width: 600px; height: 70px; resize: none">' . $category['model']->seo_description . '</textarea></div>';
            }
        ],
    ],
    'idGetter' => function ($category) {
        return $category['model']['id'];
    }
]); ?>

<script>
  $(function () {
    $('.js-count').each(function () {
      const $container = $(this);

      const min = +$container.data('min');
      const max = +$container.data('max');

      const $count = $('<span/>').addClass('symbols-count').appendTo($container);

      function update() {
        const length = $container.find('textarea').val().length;
        $count.text(length + '/' + max);

        $container.toggleClass('invalid', length > max || length < min);
      }

      $(this).find('textarea').on('keyup', function () {
        update();

        $.post('/admin/categories/update-seo/' + $container.data('id'), {
          title: $(this).parents('tr').find('.js-title-val').val(),
          description: $(this).parents('tr').find('.js-description-val').val(),
        });
      });

      update();
    });
  });
</script>

<style>
  .js-count .symbols-count {
    display: block;
    font-style: italic;
    padding: 4px;
  }

  .js-count.invalid .symbols-count {
    background: #ffb4be;
  }
</style>
