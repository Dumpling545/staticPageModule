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
use app\modules\StaticPageModule\configuration\Constants;
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= Html::encode($model->tag)?></title>
    </head>
    <body>
        <h1>
            <?= Html::encode($model->tag) ?>
        </h1>
        <div id="content">
            <h3> Pages: </h3>
            <div style="display : inline-block;">
                <p>
                    Sort by: 
                    <?= Html::a('header', ['/page/tag/'.$model->tag.'/1', 'sortBy' => Constants::SORT_BY_HEADER]) ?> |
                    <?= Html::a('rating', ['/page/tag/'.$model->tag.'/1', 'sortBy' => Constants::SORT_BY_RATING]) ?> |
                    <?= Html::a('date of last modification', ['/page/tag/'.$model->tag.'/1', 'sortBy' => Constants::SORT_BY_DATE_LAST_MODIFIED]) ?> |
                    <?= Html::a('date of creation (new to old)', ['/page/tag/'.$model->tag.'/1', 'sortBy' => Constants::SORT_BY_NEW_DATE_TO_OLD]) ?> |
                    <?= Html::a('date of creation (old to new)', ['/page/tag/'.$model->tag.'/1', 'sortBy' => Constants::SORT_BY_OLD_DATE_TO_NEW]) ?> 
                </p>
            </div>
            <ul>
                <?php foreach ($model->pages as $page): ?>
                    <li>
                        <div style="display: block;">
                            <h4><em> <?= Html::encode($page->header) ?> </em></h4> 
                            <p> <?= Html::encode($page->summary) ?> </p>
                            <p> Last Edit: <?= Html::encode($page->dateLastModified) ?> </p>
                            <p><?= Html::a('see page ->', ['/page/'.$page->slug]) ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </body>
</html>

