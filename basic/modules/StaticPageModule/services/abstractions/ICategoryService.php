<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\services\abstractions;
use app\modules\StaticPageModule\models\category\{DeleteCategoryModel, CreateCategoryModel};
/**
 *
 * @author admin
 */
interface ICategoryService {
    function getCategory(int $id, int $status);
    function getCategoryId(string $slug);
    function createCategory(CreateCategoryModel $model);
    function deleteCategory(DeleteCategoryModel $model);
    function getCategoryNames(int $status, bool $hide_byLink);
}
