<?php


namespace app\modules\StaticPageModule\models\staticPage;

use yii\base\Model;

class UpdatePageModel extends Model{
    
    public $id;
    public $categoryId;
    public $tags;      
    public $accessibilityStatus;
    public $header;
    public $summary;
    public $description;
    public $slug;
    public function rules()
    {
        return [
            [['id', 'header', 'categoryId', 'accessibilityStatus', 'summary', 'description', 'tags'], 'required'],
            [['categoryId', 'accessibilityStatus', 'id'], 'integer'],
            [['slug', 'header', 'tags', 'summary'],'string'],
            ['description', 'string', 'length' => [200, 65534]],
            ['slug', 'trim'],
            ['slug',  'string', 'length' =>[3, 15]],
            ['header',  'string', 'length' =>[5, 30]],
            ['summary', 'string',  'length' =>[30, 250]],
            ['slug', 'compare', 'compareValue' => 'slug', 'operator' => '!=']
        ];
    }
}
