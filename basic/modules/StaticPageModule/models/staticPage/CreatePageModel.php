<?php

namespace app\modules\StaticPageModule\models\staticPage;

use yii\base\Model;

 class CreatePageModel extends Model{

    public $slug;
    public $header;
    public $categoryId;
    public $tags;    
    //public $rating;    
    public $accessibilityStatus;   
    public $summary;
    public $description;
    public function rules()
    {
        return [
            [['slug', 'header', 'categoryId', 'accessibilityStatus', 'summary', 'description', 'tags'], 'required'],
            [['categoryId', 'accessibilityStatus'], 'integer'],
            [['slug', 'header', 'tags', 'summary'],'string'],
            ['description', 'string', 'length' => [200, 65534]],
            ['slug', 'trim'],
            ['slug', 'string',  'length' =>[3, 15]],
            ['header', 'string',  'length' =>[5, 30]],
            ['summary', 'string', 'length' =>[30, 250]],
            ['slug', 'compare', 'compareValue' => 'slug', 'operator' => '!=']
        ];
    }
    
}
