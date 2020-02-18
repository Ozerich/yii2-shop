<?
$this->title = 'Экспорт товаров';

use ozerich\shop\models\Manufacture;
use ozerich\shop\modules\admin\widgets\CategoryWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<div class="custom-box shadow">
    <h4 class="title">Выбор категории</h4>
    <div class="steps">
        <div class="item active">
            <i class="fa fa-toggle-on"></i>
        </div>
        <div class="item" tab="second">
            <i class="fa fa-cog "></i>
        </div>
        <div class="item" tab="finish">
            <i class="fa fa-cloud-download"></i>
        </div>
    </div>
    <div class="tabs">
        <div class="body">
            <div class="tab active" tab="first">
                <div class="row">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="col-xs-5">
                        <?= $form->field($model, 'category_id')
                            ->widget(CategoryWidget::class, [
                                'onlyCatalog' => true
                            ]) ?>
                    </div>
                    <div class="col-xs-5">
                        <?= $form->field($model, 'manufacture_id')->dropDownList(
                            ['all' => 'Все'] +  ArrayHelper::map(Manufacture::find()->all(), 'id', 'name')
                        ) ?>
                    </div>
                    <div class="col-xs-5">
                        <?= Html::checkbox('without_price', false, ['label' => 'Без цены']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

            </div>
            <div class="tab" tab="second">

            </div>
            <div class="tab" tab="finish">

            </div>
            <div class="loading">
                <i class="fa fa-spin fa-refresh"></i>
            </div>
        </div>
        <div class="footer">
            <button type="submit" class="btn btn-custom next" data="first">Далее</button>
        </div>
    </div>
</div>
<style>
    .custom-box{
        border-radius: 0 0 25px 0;
        padding: 30px;
        background:#fff;
        margin: 0 20px;
    }
    .shadow{
        box-shadow: 15px 15px 28px 0px rgba(0, 0, 0, 0.07);
    }
    h4.title{
        text-align: center;
        font-weight: 600;
        margin: 25px 0 40px;
        font-size: 20px;
    }
    .steps{
        display: flex;
        align-items: center;
        justify-content: space-around;
        height: 50px;
        margin: 30px auto;
        width: 600px;
    }
    .steps .item{
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #dadee3;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.5s all ease;
        transition-delay: 0.3s;
        position: relative;
    }
    .steps .item::before, .steps .item::after{
        content: '';
        position: absolute;
        height: 2px;
        left: -152px;
        top: 50%;
        transform: translateY(-50%);
        transition: 0.3s all cubic-bezier(.79,-0.28,.24,1.26);
        width: 152px;
        background: #dadee3;
    }
    .steps .item::after{
        width: 0px;
        left: -152px;
        background: #5e8cd1;
    }
    .steps .item:first-child:before, .steps .item:first-child:after{
        content: none;
    }
    .steps .item.active::after{
        width: 152px;
    }
    .steps .item.active{
        background: #5e8cd1;
    }
    .steps .item i.fa{
        color: #fff;
        font-size: 18px;
    }
    .tabs .tab{
        transition: 0.3s all ease;
        padding: 25px 15px;
    }
    .tabs .body{
        overflow: hidden;
        position: relative;
    }
    .tabs .body .loading{
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        opacity: 0;
        visibility: hidden;
        transition: 0.3s all ease;
    }
    .tabs .body .loading i{
        color: #5e8cd1;
        font-size: 22px;
    }
    .tabs .body.loading .loading{
        opacity: 1;
        visibility: visible;
    }
    .tabs .body.loading{
        overflow: hidden;
    }
    .btn-custom{
        background: #5e8cd1;
        color:#fff;
        font-weight: 400;
        transition: 0.3s all ease;
    }
    .btn-custom:hover{
        color:#fff;
    }
    .btn-custom:active{
        background: #5179b4;
        box-shadow: none;
        color:#fff;
    }
    .tabs .tab{
        position: relative;
        transition: 0.3s all ease;
        left: 0;
        opacity: 0;
        visibility: hidden;
        position: absolute;
    }
    .tabs .tab.active{
        opacity: 1;
        visibility: visible;
        position: relative;
    }
    .tabs .tab.left{
        left:-100%;
    }
    .secret{
        font-size: 12px;
        color: #474747;
    }

</style>
<script>
    const tabs = ['first', 'second', 'finish'];
    let currentTab = tabs[0];
    let currentTabId = 0;

    $('.steps .item').on('click', function () {
      $(this).addClass('active');
    })

    $('button.next').on('click', function () {
      if(currentTabId !== 2) {
        if(++currentTabId <= 2) {
          $(this).attr('data', tabs[currentTabId])
        }
        changeTab($(this).attr('data'))
      }
    })

    function changeTab(tab){
      $('.tabs .body').addClass('loading')
      $('button.next').attr('disabled', '')
      $('.tabs .tab').removeClass('active');
      $('.tabs .tab[tab="' + tab + '"]').addClass('active');
      $('.steps .item[tab="' + tab + '"]').addClass('active');
      $('.tabs .tab[tab="' + tabs[currentTabId-1] + '"]').addClass('left');
      if(tab === tabs[1]) {
        loadSecondStep()
      } else loadFinishStep()
    }

    function loadSecondStep() {
      $('h4.title').html('Просмотр позиций')

      let id = $('#dynamicmodel-category_id').val();
      let mid = $('#dynamicmodel-manufacture_id').val();
      let price = $('input[name="without_price"]').prop('checked') ? true : false;
      $.ajax({
        url: `/api/category/export-preview?id=${id}&manufacture_id=${mid}&without_price=${price}`,
        type: 'get',
        success: res => {
          $('button.next').removeAttr('disabled')
          $('.tabs .body').removeClass('loading')
          $('.tabs .tab[tab="' + tabs[currentTabId] + '"]').html(res);
        },
        error: res => {
          $('button.next').removeAttr('disabled')
          $('.tabs .body').removeClass('loading')
          console.log(res)
          alert('Ошибка');
        }
      });
    }

    function loadFinishStep() {
      $('h4.title').html('Экспорт')
      $('button.next').fadeOut(300)

      let params = [], filename = $('#filename').val();
      let id = $('#dynamicmodel-category_id').val();
      let mid = $('#dynamicmodel-manufacture_id').val();
      let price = $('input[name="without_price"]').prop('checked') ? true : false;

      $('tr.row__param').each(function () {
        params.push($(this).attr('data-param-name'));
      });
      $.ajax({
        url: `/api/category/export?id=${id}&manufacture_id=${mid}&without_price=${price}`,
        type: 'post',
        data: {
          filename: filename,
          params: params
        },
        success: res => {
          $('.tabs .body').removeClass('loading')
          $('.tabs .tab[tab="' + tabs[currentTabId] + '"]').html(`
            <a href=${res} target="_blank">Скачать файл </a>
            <div class="secret">Пароль от файла: BelmebelExp</div>
        `);
          location.href = res;
        },
        error: res => {
          $('.tabs .body').removeClass('loading')
          console.log(res)
          alert('Ошибка');
        }
      });
    }


    $('label[for=dynamicmodel-category_id]').html('Категория');
    $('label[for=dynamicmodel-manufacture_id]').html('Производитель');
    $('label[for=dynamicmodel-category_id]').html('Категория');

</script>
