<?
$this->title = 'Импорт цен';
use ozerich\shop\modules\admin\widgets\CategoryWidget;
use yii\widgets\ActiveForm;
?>
<div class="box box-primary">
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissiblex" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <span></span>
                </div>
            </div>

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="col-xs-5">
                <?= $form->field($model, 'file')->fileInput() ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary" disabled="">Импорт</button>
    </div>
    <div class="progress progress-sm active" style="display: none">
        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>
<script>
    $('#dynamicmodel-file').on('change', () => {
      $('.box-footer button').removeAttr('disabled')
    });
    let interval = null, intervalValue = 0;
    $('label[for=dynamicmodel-category_id]').html('Категория');
    $('label[for=dynamicmodel-file]').html('Файл для импорта');
    $('.box-footer button').on('click', e => {
      e.preventDefault;
      $('button').attr('disabled', '')
      $('.progress').fadeIn(300)
      const file_data = $('#dynamicmodel-file').prop('files')[0];
      const form_data = new FormData();
      form_data.append('file', file_data);
      interval = setInterval(intervalIncrement, 200);
      $.ajax({
        url: '/api/category/import',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: res => {
          $('button').removeAttr('disabled')
          $('.progress').fadeOut(300)
          clearInterval(interval)
          intervalValue = 0
          $('.alert').fadeIn();
          $('.alert').removeClass('alert-success');
          $('.alert').removeClass('alert-danger');
          if(res != 'false'){
            $('.alert').addClass('alert-success');
            $('.alert span').html(res)
          } else {
            $('.alert').addClass('alert-danger');
            $('.alert span').html('Во время импорта произошла ошибка')
          }
        },
        error: res => {
          $('button').removeAttr('disabled')
          $('.progress').fadeOut(300)
          clearInterval(interval)
          intervalValue = 0
          $('.alert').fadeIn();
          $('.alert').removeClass('alert-success');
          $('.alert').addClass('alert-danger');
          $('.alert span').html('Ошибка на сервере')
        }
      });
    });
    function intervalIncrement() {
        if(++intervalValue < 100){
            $('.progress-bar').attr('aria-valuenow', intervalValue);
            $('.progress-bar').css('width', intervalValue + '%');
        } else {
          clearInterval(interval)
        }
    }
</script>
