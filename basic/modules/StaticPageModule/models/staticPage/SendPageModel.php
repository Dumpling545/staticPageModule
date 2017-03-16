<?php


namespace app\modules\StaticPageModule\models\staticPage;

use yii\base\Model;

class SendPageModel extends Model{
    public $id;
    /**
     * slug - (человекопонятный адрес, позволяющий однозначно идентифицировать
     *  страницу), может состоять из [a-zA-Z0-9\-_], например «contact»
     **/
    public $header;
    public $slug;  
    public $tags;   
    public $dateCreated;
    public $rating;
    public $author;
    public $description;
    public $accessibilityStatus;
    public $summary;
    public $categoryId;
}
