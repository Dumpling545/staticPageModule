<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\models\category;

use yii\base\Model;

class CreateCategoryModel extends Model{
    
    public $parentId;
    public $name;
    public $slug;
    public $accessibilityStatus;
    const SCENARIO_CREATE_CATEGORY = 'category';
    const SCENARIO_CREATE_PAGE_AND_CATEGORY = 'pageAndCategory';
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE_CATEGORY => ['parentId', 'name', 'slug', 'accessibilityStatus'],
            self::SCENARIO_CREATE_PAGE_AND_CATEGORY => ['parentId', 'name', 'slug', 'accessibilityStatus']
        ];
    }
    public function rules()
    {
        return [
            [['slug', 'parentId', 'name', 'accessibilityStatus'], 'required', 'on' => self::SCENARIO_CREATE_CATEGORY],
            [['parentId', 'accessibilityStatus'], 'integer'],
            [['name', 'slug'], 'string'],
            ['slug', 'trim'],
            ['slug',  'string', 'length' =>[3, 15]],
            ['name', 'string',  'length' =>[5, 30]],
            ['slug', 'compare', 'compareValue' => 'slug', 'operator' => '!=']
        ];
    }
    
}