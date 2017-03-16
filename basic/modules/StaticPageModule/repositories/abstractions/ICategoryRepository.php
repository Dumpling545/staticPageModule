<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\repositories\abstractions;

use app\modules\StaticPageModule\repositories\entities\Category;
interface ICategoryRepository {
    public function createCategory(Category $entity);
    public function getCategory(int $id, int $status);
    public function getAllCategoryNames(int $status, bool $hide_byLink);
    public function getCategoryId(string $slug);
    public function getChildrenCategories(int $id, int $status,  bool $hide_byLink);
    public function hasCategoryId(int $id);
}
