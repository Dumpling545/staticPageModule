<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <p> Pages: </p>
        <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'slug',
            'header',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}{rating}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('View', ['/page/'.$model['slug']])." ";
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('Update', ['/update/page/'.$model['slug']])." ";
                    },
                    'delete' =>  function ($url, $model, $key) {
                        //url
                        return Html::a('Delete', ['/delete/page/'.$model['slug']])." ";
                    },
                    'rating'  => function ($url, $model, $key) {
                        return Html::button('Clear rating',['class' => 'nullRatingButton', 'data-slug' => $model['slug'], 'data-name' => $model['header']]); //['/null-rating/page/'.$model['slug']]);
                    }
                ]     
            ]
        ],
    ]); ?>
<script type="text/javascript">  
    $(document).ready(function() {
        $('.nullRatingButton').click(function(){
            var name = $(this).attr('data-name');
            $.ajax({
               url: "<?php echo Url::toRoute('/null-rating/page') ?>",
               type: 'get',
               data: {
                         slug: $(this).attr('data-slug') , 
                         _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                     },
               success: function (data) {
                  alert("Rating of page " + name + " is cleared");
               },
                error: function (jqXHR, textStatus, errorThrown) {
                  alert('Error on clearing rating of page ' + name + ' ' + errorThrown);
                }
          });
        });
    });
</script>

    
