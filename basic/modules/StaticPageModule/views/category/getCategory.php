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
           <h3> Pages: </h3>
            <div style="display : inline-block;">
                <p>
                    Sort by: 
                    <?= Html::a('header', ['/page/category/'.$model->slug.'/1', 'sortBy' => Constants::SORT_BY_HEADER]) ?> |
                    <?= Html::a('rating', ['/page/category/'.$model->slug.'/1', 'sortBy' => Constants::SORT_BY_RATING]) ?> |
                    <?= Html::a('date of last modification', ['/page/category/'.$model->slug.'/1', 'sortBy' => Constants::SORT_BY_DATE_LAST_MODIFIED]) ?> |
                    <?= Html::a('date of creation (new to old)', ['/page/category/'.$model->slug.'/1', 'sortBy' => Constants::SORT_BY_NEW_DATE_TO_OLD]) ?> |
                    <?= Html::a('date of creation (old to new)', ['/page/category/'.$model->slug.'/1', 'sortBy' => Constants::SORT_BY_OLD_DATE_TO_NEW]) ?> 
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

