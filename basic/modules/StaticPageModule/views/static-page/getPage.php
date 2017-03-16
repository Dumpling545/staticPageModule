<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php   
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\modules\StaticPageModule\widgets\RatingPicker;
    ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>
            <?= Html::encode($model->header) ?>
        </h1>
        <div id="tags"> 
            <?php for($i = 0; $i < count($model->tags); $i++){ ?>
                <span><?= Html::a($model->tags[$i], ['/page/tag/'.$model->tags[$i].'/1']) ?></span>
                <?php if($i != count($model->tags) - 1) 
                    echo Html::encode(", "); ?>
            <?php } ?>
        </div>
        <div id = "date">
            <p> Date of publication:  <?= Html::encode($model->dateCreated) ?></p>
        </div>
        <div id="content">
            <?= HtmlPurifier::process($model->description) ?>
        </div>
        <div id="rating">
            <?= RatingPicker::widget(['pageId' => $model->id, 'rating' => $model->rating, 'canRateModel' => $canRateModel]) ?>
        </div>
    </body>
</html>
