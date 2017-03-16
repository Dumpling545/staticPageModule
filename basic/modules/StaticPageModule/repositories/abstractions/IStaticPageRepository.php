<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\repositories\abstractions;

use app\modules\StaticPageModule\repositories\entities\{StaticPage, TagStaticPageMembership, Category, StaticPageUpdateEntity};
 interface IStaticPageRepository {
    public function getTagsOfPage(int $id);
    public function createPage(StaticPage $entity, array $tagsToEntity, Category $category);
    public function getPageBySlug(string $slug, int $status);
    public function updatePage(StaticPageUpdateEntity $entity, array $tagsToEntity);
    public function deletePage(int $id);
    public function getPagesByTag(string $tag, int $status);
    public function getPagesByCategory(int $categoryId, int $status);
    public function setRating(int $id, int $rating, string $date);
    public function getSlugById(int $id);
    public function getIdBySlug(string $slug);
    public function hasPageSlug(string $slug);
    public function getAllPagesQuery($author);
    public function getAuthorById(int $id);
}
