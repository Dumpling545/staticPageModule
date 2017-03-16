<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use yii\helpers\Url;?>
<style id="main">
#ratingStars {
  unicode-bidi: bidi-override;
  direction: rtl;
}
#ratingStars button {
    padding: 0;
    margin: 0;
    background-color: rgba(0, 0, 0, 0);
    border-color: rgba(0, 0, 0, 0);
}

</style>
<style id='styleSheet'>
.rating > button {
  display: inline-block;
  position: relative;
  width: 1.1em;
}
.rating > button:hover:before,
.rating > button:hover ~ button:before {
   content: "\2605";
   position: absolute;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<p id="canRate" hidden><?=  Html::encode($canRateModel->canRate) ?></p>
<div id="ratingStars" class="rating">
    <span>(<span><?= Html::encode($model->rating) ?></span>)</span>
        <button id="5but" data-count="5">☆</button>
        <button id="4but" data-count="4">☆</button>
        <button id="3but" data-count="3">☆</button>
        <button id="2but" data-count="2">☆</button>
        <button id="1but" data-count="1">☆</button>
</div>
<script type="text/javascript">
    
    $(document).ready(function() {
        var cantRate = '<?php echo $canRateModel->canRate?>'=== '0';
        if(cantRate){
            $('#styleSheet').remove();
        }
        for(var i = 1; i < 6; i++){
            if(!cantRate){
                $("#"+i+"but").click(function(){
                    var count = parseInt($(this).attr('data-count'));
                    var but = $(this);
                    $.ajax({
                       url: "<?php echo Url::toRoute('/add-rating') ?>",
                       type: 'get',
                       data: {
                            pageId: '<?php echo $model->pageId ?>',
                            rating: count , 
                            _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                        },
                       success: function (data) {
                          alert("Rating of page " + name + " is added");
                          cantRate = true;
                          $('#styleSheet').remove();
                          but.off('click');
                          $('#ratingStars span span').html(count);
                          for(var j = count; j >0; j--){
                              $("#"+j+"but").html('★');
                            }
                       },
                        error: function (jqXHR, textStatus, errorThrown) {
                          alert('Error on adding your rating of page ' + name + ' ' + errorThrown);
                        }
                    });
                });
               $("#"+i+"but").hover(
                    function(){
                        $('#ratingStars').addClass("rating");
                    },
                    function(){
                        $('#ratingStars').removeClass("rating");
                    });
            }else if(Math.round(parseInt($('#ratingStars span span').html())) >= i){
                $("#"+i+"but").html('★');
            }
        }
    });
</script>