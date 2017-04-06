<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\services\implementations;
use app\modules\StaticPageModule\repositories\entities\{TagStaticPageMembership, StaticPage, StaticPageUpdateEntity};
use app\modules\StaticPageModule\models\staticPage\{SendTagModel, GetPageByTagModel, CreatePageModel,GetPageModel,SendPageModel,
 UpdatePageModel,PageItem};
use app\modules\StaticPageModule\models\category\{ CreateCategoryModel};
use app\modules\StaticPageModule\repositories\entities\Category;
use app\modules\StaticPageModule\services\abstractions\IStaticPageService;
use \app\modules\StaticPageModule\repositories\implementations\{StaticPageRepository,CategoryRepository};
use app\modules\StaticPageModule\configuration\Constants;
use Yii;
class StaticPageService implements IStaticPageService{
    private  $pageRepository;
    private  $categoryRepository;
    public function __construct() {
        $this->pageRepository = new StaticPageRepository();
        $this->categoryRepository = new CategoryRepository();
    } 
    private function createPageWithoutCategory(CreatePageModel $model, string $author){
        $cache = Yii::$app->cache;
        if(!$cache->exists(Constants::PAGE_ID_KEY)){
            $cache->add(Constants::PAGE_ID_KEY, -1);
        }
        $cache[Constants::PAGE_ID_KEY] = $cache[Constants::PAGE_ID_KEY] + 1;
        
        $entity = new StaticPage();
        $entity->id = $cache[Constants::PAGE_ID_KEY];
        $entity->author = $author;
        $entity->accessibilityStatus = $model->accessibilityStatus;
        $entity->categoryId = $model->categoryId;
        $entity->rating = 0;
        $entity->slug = $model->slug;
        $entity->summary = $model->summary;
        $entity->description = $model->description;
        $entity->header = $model->header;
        
        $tagsToEntity = array();
        foreach ($model->tags as $tag){
            $membership = new TagStaticPageMembership();
            $membership->pageId = $cache[Constants::PAGE_ID_KEY];
            $membership->tagName = $tag;
            array_push($tagsToEntity, $membership);
        } 
        
        $entity->dateCreated = date(DATE_RSS);
        $entity->dateLastModified = $entity->dateCreated;
        return ['entity' => $entity, 'tagsToEntity' => $tagsToEntity];
    }
    public function createPage(CreatePageModel $model, string $author) {
        $model->tags = explode(" ", $model->tags);
        try{
        $result = $this->createPageWithoutCategory($model, $author);
        $this->pageRepository->createPage($result['entity'], $result['tagsToEntity'], new Category());
        } catch(\Exception $e){
            throw $e;
        }
        return $result['entity']->id;
    }
    public function createPageAndCategory(CreatePageModel $model, CreateCategoryModel $c_model, string $author){
        $model->tags = explode(" ", $model->tags);
            $cache = Yii::$app->cache;
            if(!$cache->exists(Constants::CATEGORY_ID_KEY)){
                $cache->add(Constants::CATEGORY_ID_KEY, -1);
            }
        $cache[Constants::CATEGORY_ID_KEY] = $cache[Constants::CATEGORY_ID_KEY] + 1;
        $category = new Category();
        $category->id = $cache[Constants::CATEGORY_ID_KEY];
        $model->categoryId=$category->id;
        $result = $this->createPageWithoutCategory($model, $author);
        $category->name = $c_model->name;
        $category->parentId = $c_model->parentId;
        $category->slug = $c_model->slug;
        $category->accessibilityStatus = $c_model->accessibilityStatus;
        try{
            $this->pageRepository->createPage($result['entity'], $result['tagsToEntity'], $category);
        } catch(\Exception $e){
            throw $e;
        }
        return $result['entity']->id;
    }

    public function deletePage(int $id) {
        try{
            $this->pageRepository->deletePage($id);
        } catch(\Exception $e){
            throw $e;//new \Exception("Page doesn't exist");
        }
    }
    public function getPageBySlug(string $slug, int $status) {
        if($this->pageRepository->hasPageSlug($slug)){
            try{
                $entity = $this->pageRepository->getPageBySlug($slug, $status); 
                $model = new SendPageModel();
                $model->id = $entity->id;
                $model->slug = $entity->slug;
                $model->accessibilityStatus = $entity->accessibilityStatus;
                $model->summary = $entity->summary;
                $model->author = $entity->author;
                $model->categoryId = $entity->categoryId;
                $model->header = $entity->header;
                $model->rating = $entity->rating;
                $model->description= $entity->description;
                $model->dateCreated = $entity->dateCreated;
                $model->tags = array_column($this->pageRepository->getTagsOfPage($this->getIdBySlug($slug)), 'tagName');
                return $model;
            } catch(\Exception $e){
                Yii::error('Not Allowed: Error at line '.$e->getLine().' in file '.$e->getFile().' : '.$e->getMessage());
                throw new \Exception("You are not allowed to do this action");
            }
        } else {
            throw new \Exception("Page doesn't exist");
        }
    }
    public function setRating(int $pageId, int $rating) {
        try{
            $this->pageRepository->setRating($pageId, $rating, date(DATE_RSS));
        } catch(\Exception $e){
            throw $e;
        }
    }

    public function updatePage(UpdatePageModel $model) {
        $entity = new StaticPageUpdateEntity();
        $entity->id = $model->id;
        $entity->slug = $model->slug;
        $entity->accessibilityStatus = $model->accessibilityStatus;
        $entity->categoryId = $model->categoryId;
        $entity->summary = $model->summary;
        $entity->description = $model->description;
        $entity->header = $model->header;
        $entity->dateLastModified = date(DATE_RSS);
        $tags = explode(" ", $model->tags);
        $tagToEntity = array();
        for($i = 0; $i < count($tags); $i++){
            $membership = new TagStaticPageMembership();
            $membership->tagName = $tags[$i];
            $membership->pageId = $model->id;
            array_push($tagToEntity, $membership);
        }
        try{
            $this->pageRepository->updatePage($entity, $tagToEntity);
        } catch(\Exception $e){
            throw $e;
        }
    }

    public function getPagesByTag(string $tag, int $status) {
        $model = new SendTagModel();
        $s_array = array();
        $array = $this->pageRepository->getPagesByTag($tag, $status);
        foreach($array as $item){
            $element = new PageItem();
            $element->dateLastModified = $item['dateLastModified'];
            $element->header = $item['header'];
            $element->summary = $item['summary'];
            $element->slug = $item['slug'];
            $element->rating = $item['rating'];
            $element->dateCreated = $item['dateCreated'];
            array_push($s_array, $element);
        }
        $model->pages = $s_array;
        $model->tag = $tag;
        return $model;
    }

    public function getSlugById(int $id) {
        $query = $this->pageRepository->getSlugById($id);
        return $query->slug;
    }

    public function getIdBySlug(string $slug) {
        $query = $this->pageRepository->getIdBySlug($slug);
        return $query->id;
    }

    public function getAllPagesQuery($author) {
        return $this->pageRepository->getAllPagesQuery($author);
    }

    public function getAuthorById(int $id) {
        try{
            $author = $this->pageRepository->getAuthorById($id)['author'];
            return $author;
        } catch (Exception $ex) {
            throw $e;
        }
    }

}
