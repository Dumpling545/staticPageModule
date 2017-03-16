<?php


namespace app\modules\StaticPageModule\models\staticPage;

use yii\base\Model;

class PageItem extends Model{
    public $slug;
    public $header;
    public $dateLastModified;
    public $summary;
    public $dateCreated;
    public $rating;
}
