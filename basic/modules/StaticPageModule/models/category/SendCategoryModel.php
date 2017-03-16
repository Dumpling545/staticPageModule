<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\models\category;

use yii\base\Model;
class SendCategoryModel extends Model{
    public $id;
    public $slug;  
    public $pages;
    public $childrenCategories;
    public $name;
    public $accessibilityStatus;
}
