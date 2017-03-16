<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\LinkPager;
use app\modules\StaticPageModule\models\PageItem;
use app\modules\StaticPageModule\configuration\Constants;
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= Html::encode($model->name) ?></title>
    </head>
    <body>
        <h1>
            <?= Html::encode($model->name) ?>
        </h1>
        
        <div id="content">
            <h3> Categories: </h3>
            <ul>
                <?php foreach ($model->childrenCategories as $child): ?>
                    <li>
                        <h4><em><?= Html::encode($child->name) ?></em></h4>
                        <p><?= Html::a('see category ->', ['/page/category/'.$child->slug.'/1']) ?></p>
                    </li>
                <?php endforeach; ?>
              
            </ul>
           
        </div>
    </body>
</html>

