<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\repositories\entities;

/**
 * Description of Category
 *
 * @author admin
 */
class Category{
    
    public $id;
    public $parentId;
    public $name;
    public $slug;
    public $accessibilityStatus;
    
}
