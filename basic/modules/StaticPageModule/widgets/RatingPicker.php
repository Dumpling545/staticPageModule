<?php

namespace app\modules\StaticPageModule\widgets;

use yii\base\Widget;
use app\modules\StaticPageModule\models\ratingItem\{AddRatingItemModel, CanRateModel};
class RatingPicker extends Widget{
    public $rating;
    public $pageId;
    public $canRateModel;
    public function init() {
        parent::init();
        //$this->canRateModel = new CanRateModel();
        //Yii::app()->clientScript->registerCssFile('views/css/stylesheet.css');
    }
    public function run()
    {
        $model = new AddRatingItemModel();
        $model->pageId = $this->pageId;
        $model->rating = $this->rating;
        return $this->render('rating', [
            'model' => $model,
            'canRateModel'=>$this->canRateModel
            ]);
    }
}
