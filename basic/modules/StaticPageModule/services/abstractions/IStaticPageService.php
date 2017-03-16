<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\services\abstractions;
use app\modules\StaticPageModule\models\staticPage\{CreatePageModel, UpdatePageModel, DeletePageModel};
/**
 *
 * @author admin
 */
interface IStaticPageService {
    function getPageBySlug(string $slug, int $status);
   function setRating(int $pageId, int $rating); 
   //function addRating($ipAddress, $rating);
   function createPage(CreatePageModel $model, string $author);
   function updatePage(UpdatePageModel $model);
   function deletePage(int $id);
   function getPagesByTag(string $tag, int $status);
   function getSlugById(int $id);
   function getIdBySlug(string $slug);
   function getAllPagesQuery($author);
   function getAuthorById(int $id);
}
