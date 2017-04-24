<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\services\implementations;
use Yii;
use app\modules\StaticPageModule\models\staticPage\PageItem;
use app\modules\StaticPageModule\models\category\{ChildItemModel,SendCategoryModel,BaseStaticPage, CreateCategoryModel, DeleteCategoryModel};
use app\modules\StaticPageModule\services\abstractions\ICategoryService;
use app\modules\StaticPageModule\configuration\Constants;
use app\modules\StaticPageModule\repositories\implementations\{StaticPageRepository, CategoryRepository};
use app\modules\StaticPageModule\repositories\entities\{Category};
class CategoryService implements ICategoryService{
    private $pageRepository;
    private $categoryRepository;
    public function __construct() {
        $this->pageRepository = new StaticPageRepository();
        $this->categoryRepository = new CategoryRepository();
    }
    public function getCategory(int $id, int $status) {
        if($this->categoryRepository->hasCategoryId($id) || $id=-1){
            $category = null;
            try{
            if($id != -1)
                $category = $this->categoryRepository->getCategory($id, $status);
            else
                $category = new Category();
            //slug, header, dateLastModified, summary
            $pagesByCategory = array();
            if($id != -1){
                foreach($this->pageRepository->getPagesByCategory($id, $status) as $page){
                    $element = new PageItem();
                    $element->dateLastModified = $page['dateLastModified'];
                    $element->header = $page['header'];
                    $element->summary = $page['summary'];
                    $element->slug = $page['slug'];
                    $element->rating = $page['rating'];
                    $element->dateCreated = $page['dateCreated'];
                    array_push($pagesByCategory, $element);
                }
            }
            $model = new SendCategoryModel();
            $model->pages = $pagesByCategory;
            $children = array();
            foreach($this->categoryRepository->getChildrenCategories($id, $status) as $child){
                $obj = new ChildItemModel();
                $obj->name = $child['name'];
                $obj->slug = $child['slug'];
                array_push($children, $obj);
            }
            $model->childrenCategories = $children;
            $model->id = $id;
            if($id != -1){
                $model->accessibilityStatus = $category->accessibilityStatus;
                $model->name = $category->name;
                $model->slug = $category->slug;
            } else {
                $model->accessibilityStatus = 0;
                $model->name = "All categories";
                $model->slug = "slug";
            }
            return $model;
            } catch(\Exception $e){
                throw new \Exception("You are not allowed to this action");
            }
        }
    }
    
    /*public function compareItemsByHeader (BaseStaticPage $a, BaseStaticPage $b){
        return (strnatcasecmp($a->header, $b->header) > 0);
    }
    private function compareItemsByHeader (PageItem $a, PageItem $b){
        return (strnatcasecmp($a->header, $b->header) > 0);
    }
    private function compareItemsByNewDateToOld (PageItem $a, PageItem $b){
        return (strtotime($a->dateCreated) - strtotime($b->dateCreated)) < 0;
    }
    private function compareItemsByOldDateToNew (PageItem $a, PageItem $b){
        return !$this->compareItemsByNewDateToOld($a,$b);
    }
    private function compareItemsByDateLastModified (PageItem $a, PageItem $b){
        return (strtotime($a->dateLastModified) - strtotime($b->dateLastModified)) < 0;
    }
    private function compareItemsByRating (PageItem $a, PageItem $b){
        return $a->rating < $b->rating;
    }*/

    public function getCategoryId(string $slug) {
        try{
        return $this->categoryRepository->getCategoryId($slug)->id;
        } catch(\Exception $e){
            throw new \Exception("Slug doesn't exist");
        }
    }

    public function createCategory(CreateCategoryModel $model) {
        try{
            $entity = new Category();
            $entity->accessibilityStatus = $model->accessibilityStatus;
            $entity->id = -1;
            $entity->name = $model->name;
            $entity->slug = $model->slug;
            $entity->parentId = $model->parentId;
            return $this->categoryRepository->createCategory($entity);
        } catch(\Exception $e){
            throw $e;
        }
    }

    public function deleteCategory(DeleteCategoryModel $model) {
        try{
         $this->categoryRepository->deleteCategory($model->id);
        } catch(\Exception $e){
            
        }
    }
    public function getCategoryNames(int $status, bool $hide_byLink = true) {
        $query = $this->categoryRepository->getAllCategoryNames($status, $hide_byLink);
        $result = array();
        //$result[-1] = '<create new>';
        foreach($query as $item){
            $result[$item['id']] = $item['name'];
        }
        return $result;
    }

}
