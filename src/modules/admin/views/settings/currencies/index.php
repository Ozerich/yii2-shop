<? /**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */
$this->title = 'Валюты';
?>

<?php echo ozerich\admin\widgets\ListPage::widget([
    'dataProvider' => $dataProvider,
    'headerButtons' => [
        [
            'label' => 'Добавить валюту',
            'action' => 'settings/create-currency',
            'icon' => 'plus',
            'additionalClass' => 'success'
        ]
    ],
    'columns' => [
        'name',
        'full_name',
        [
            'attribute' => 'rate',
            'format' => 'raw',
            'value' => function (\ozerich\shop\models\Currency $currency) {
                if ($currency->primary) {
                    return 'Базовая валюта';
                }

                return '<input type="number" step="0.01" class="form-control js-rate-value" data-currency-id="' . $currency->id . '" value="' . $currency->rate . '">';
            }
        ],
    ],
    'actions' => ['edit' => 'update-currency', 'delete' => 'delete-currency']
]); ?>

<script>
  $('body').on('change', '.js-rate-value', function () {
    $.post('/admin/settings/change-rate', {
      id: $(this).data('currency-id'),
      value: parseFloat($(this).val().trim().replace(',', '.'))
    });
  });
</script>
