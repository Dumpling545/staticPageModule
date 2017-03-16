<?php

namespace app\modules\StaticPageModule\models\staticPage;
use yii\base\Model;

class BaseStaticPage extends Model{
    public $summary;
    public $header;
    public $id;
    public $dateCreated;
    public $dateLastModified;
    public $rating;
}
