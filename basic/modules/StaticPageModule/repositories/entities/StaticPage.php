<?php

namespace app\modules\StaticPageModule\repositories\entities;


class StaticPage{
    
    public $id;
    public $author;
    /**
     * slug - (человекопонятный адрес, позволяющий однозначно идентифицировать
     *  страницу), может состоять из [a-zA-Z0-9\-_], например «contact»
     **/
    public $slug;
    public $header;
    public $categoryId;
    
    public $dateCreated;
    public $dateLastModified;
    
    public $rating;
    
    public $accessibilityStatus;
    
    public $summary;
    public $description;
    
}
