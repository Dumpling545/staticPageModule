<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\repositories\implementations;
use Yii;
use app\modules\StaticPageModule\repositories\entities\{Category, StaticPage, TagStaticPageMembership, StaticPageUpdateEntity};
use app\modules\StaticPageModule\repositories\abstractions\IStaticPageRepository;
class StaticPageRepository implements IStaticPageRepository{
    private $pagesTableName;
    private $tagToPageTablename;
    private $categoryTableName;
    public function __construct() {
        $this->pagesTableName = (new \ReflectionClass(new StaticPage()))->getShortName();
        $this->tagToPageTablename = (new \ReflectionClass(new TagStaticPageMembership()))->getShortName();
        $this->categoryTableName = (new \ReflectionClass(new Category()))->getShortName();
    }
    public function getAuthorById(int $id){
        if($id!=null){
            try{
            $command = Yii::$app->db->createCommand('SELECT author FROM '.$this->pagesTableName.' WHERE id=:id');
            $query = $command->bindValue(':id', $id)
            ->queryOne();
            return $query;
            } catch (\Exception $e){
                throw new \Exception("something wrong");
            }
        }
       
    }
    public function createPage(StaticPage $entity, array $tagsToEntity, Category $category) {
        if(!empty($entity) && $entity->header != null){
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $db->createCommand()->insert($this->pagesTableName, get_object_vars($entity))->execute();
                for($i = 0; $i < count($tagsToEntity); $i++){
                    $ttP = $db->createCommand();
                    $ttP->insert($this->tagToPageTablename, get_object_vars($tagsToEntity[$i]))->execute();
                }
                if(!empty($category) && $category->name != null)
                    $db->createCommand()->insert($this->categoryTableName, get_object_vars($category))->execute();
                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            throw new \InvalidArgumentException();
        }
    }

    public function deletePage(int $id) {
        if($id != null){
           
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $ttP = $db->createCommand();
                $ttP->delete($this->tagToPageTablename, "pageId = {$id}");
                $ttP->execute();
                $db->createCommand()->delete($this->pagesTableName,"id = {$id}")->execute();
                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            throw new \InvalidArgumentException();
        }
    }
    public function getPageBySlug(string $slug, int $status) {
        if($slug!= null){
            $command = Yii::$app->db->createCommand('SELECT * FROM '.$this->pagesTableName.' WHERE slug=:slug '.
                    'AND (accessibilityStatus <= :status OR (:status = 0 AND accessibilityStatus = 1))');
            $query = $command->bindValue(':slug', $slug)
                    ->bindValue(":status", $status)
            ->queryOne();
            return (object) $query;
        } else {
            throw new \InvalidArgumentException();
        }
    }

    public function getPagesByTag(string $tag, int $status) {
        if($tag != null){
            $command = Yii::$app->db->createCommand('SELECT header, slug, dateLastModified, summary, rating, dateCreated FROM '.
                    $this->pagesTableName.' JOIN '.$this->tagToPageTablename.' ON '.$this->tagToPageTablename.
                    '.pageId = '.$this->pagesTableName.'.id WHERE '.$this->tagToPageTablename.'.tagName = :tagName '.
                    'AND accessibilityStatus <> 1 AND accessibilityStatus <= :status');
            $query = $command->bindValue(':tagName', $tag)
                    ->bindValue(":status", $status)
            ->queryAll();
            return (array) $query;
        } else {
            throw new \InvalidArgumentException();
        }  
    }

    public function updatePage(StaticPageUpdateEntity $entity, array $tagsToEntity) {
        if(!empty($entity) && !empty($tagsToEntity)){
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $db->createCommand()->update($this->pagesTableName, get_object_vars($entity), 
                        "id={$entity->id}")->execute();
                $db->createCommand()->delete($this->tagToPageTablename, "pageId = {$entity->id}")->execute();
                for($i = 0; $i < count($tagsToEntity); $i++){
                    $ttP = $db->createCommand();
                    $ttP->insert($this->tagToPageTablename, get_object_vars($tagsToEntity[$i]))->execute();
                }
                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
            } else {
            throw new \InvalidArgumentException();
        }  
    }

    public function getPagesByCategory(int $categoryId, int $status) {
        if($categoryId != null){
            $command = Yii::$app->db->createCommand('SELECT slug, header, dateLastModified, summary, rating, dateCreated '.
                    'FROM '.$this->pagesTableName.' WHERE categoryId=:categoryId '.
                    'AND accessibilityStatus <> 1 AND accessibilityStatus <= :status');
            $query = $command->bindValue(':categoryId', $categoryId)
                    ->bindValue(":status", $status)
            ->queryAll();
            return (array) $query;
        } else {
            throw new \InvalidArgumentException();
        }
    }

    public function setRating(int $id, int $rating, string $date) {
        if($id!= null && ($rating >= 0 && $rating <= 5)){
            Yii::$app->db->createCommand()->update($this->pagesTableName,['rating' => $rating, 'dateLastModified' => $date] , "id={$id}")->execute();
        } else {
            throw new \InvalidArgumentException();
        } 
    }

    public function getTagsOfPage(int $id) {
        if($id!= null){
            $command = Yii::$app->db->createCommand('SELECT tagName FROM '.$this->tagToPageTablename.' WHERE pageId=:id');
            $query = $command->bindValue(':id', $id)
            ->queryAll();
            return (array) $query;
        } else {
            throw new \InvalidArgumentException();
        } 
    }

    public function getSlugById(int $id) {
        if($id != null){
            $command = Yii::$app->db->createCommand('SELECT slug FROM '.$this->pagesTableName.' WHERE id=:id');
            $query = $command->bindValue(':id', $id)
            ->queryOne();
            return (object) $query;
        } else {
            throw new \InvalidArgumentException();
        }
    }

    public function getIdBySlug(string $slug) {
        if($slug != null){
            $command = Yii::$app->db->createCommand('SELECT id FROM '.$this->pagesTableName.' WHERE slug=:slug');
            $query = $command->bindValue(':slug', $slug)
            ->queryOne();
            return (object) $query;
        } else {
            throw new \InvalidArgumentException();
        }
    }

    public function hasPageSlug(string $slug) {
        try {
            $command = Yii::$app->db->createCommand('SELECT slug FROM '.$this->pagesTableName.' WHERE slug=:slug');
            $query = $command->bindValue(':slug', $slug)
            ->queryOne();
            return $query['slug'] == $slug;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function getAllPagesQuery($author) {
        try{
            $query=null;
            if(!empty($author)){
            $query = Yii::$app->db->createCommand('SELECT slug, id, header FROM '.$this->pagesTableName." WHERE author = :author")->bindValue(":author", $author)->queryAll();
            } else {
               $query = Yii::$app->db->createCommand('SELECT slug, id, header FROM '.$this->pagesTableName)->queryAll(); 
            }
            return $query;
        } catch (Exception $ex) {
            throw $e;
        }
    }

}
