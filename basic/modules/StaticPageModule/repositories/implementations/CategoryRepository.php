<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\repositories\implementations;

use Yii;
use app\modules\StaticPageModule\repositories\abstractions\ICategoryRepository;
use app\modules\StaticPageModule\repositories\entities\Category;
class CategoryRepository implements ICategoryRepository{
    private $tableName;
    public function __construct() {
        $this->tableName = (new \ReflectionClass(new Category()))->getShortName();
    }
    public function createCategory(Category $entity) {
        if(!empty($entity)){
            Yii::$app->db->createCommand()->insert($this->tableName, get_object_vars($entity))->execute();
        } else {
            throw new InvalidArgumentException();
        }
    }

    public function getCategory(int $id, int $status) {
        if($id != null){
            $command = Yii::$app->db->createCommand('SELECT * FROM '.$this->tableName.' WHERE id=:id '.
                    'AND (accessibilityStatus <= :status OR (:status = 0 AND accessibilityStatus = 1))');
            $query = $command->bindValue(':id', $id)
                    ->bindValue(":status", $status)
            ->queryOne();
            return (object) $query;
        } else {
            throw new \InvalidArgumentException();
        }
    }

    public function getAllCategoryNames(int $status, bool $hide_byLink = true) {
        try{
            $appendix = "";
            if($hide_byLink){
                $appendix = ' AND accessibilityStatus <> 1';
            }
            $query = array();
            $command = Yii::$app->db->createCommand('SELECT slug, id, name FROM '.$this->tableName.' WHERE '.
                ' accessibilityStatus <= :status'.$appendix);
            $query = $command->bindValue(":status", $status)->queryAll();
            return (array)$query;
        } catch(\Exception $e) { 
            throw $e;
        }
    }

    public function getCategoryId(string $slug) {
        if($slug != null){
        $command = Yii::$app->db->createCommand('SELECT id FROM '.$this->tableName.' WHERE slug=:slug');
        $query = $command->bindValue(':slug', $slug)
            ->queryOne();
        return (object)$query;
        }  else {
            throw new \InvalidArgumentException();
        } 
    }

    public function getChildrenCategories(int $id, int $status, bool $hide_byLink = true) {
        $appendix = "";
        if($hide_byLink){
            $appendix = ' AND accessibilityStatus <> 1';
        }
        $command = Yii::$app->db->createCommand('SELECT slug, name FROM '.$this->tableName.' WHERE parentId=:id '.
                'AND accessibilityStatus <= :status'.$appendix);
        $query = $command->bindValue(':id', $id)
            ->bindValue(":status", $status)
            ->queryAll();
        return (array)$query;
    }

    public function hasCategoryId(int $id) {
        try {
            $command = Yii::$app->db->createCommand('SELECT id FROM '.$this->tableName.' WHERE id=:id');
            $query = $command->bindValue(':id', $id)
            ->queryOne();
            return $query['id'] == $id;
        } catch (Exception $ex) {
            return false;
        }
    }

}
