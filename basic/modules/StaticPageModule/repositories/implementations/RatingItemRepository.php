<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\repositories\implementations;

use app\modules\StaticPageModule\repositories\abstractions\IRatingItemRepository;
use app\modules\StaticPageModule\repositories\entities\{StaticPage,RatingItem};
use Yii;

class RatingItemRepository implements IRatingItemRepository{
    private $tableName;
    public function __construct() {
        $this->tableName = (new \ReflectionClass(new RatingItem()))->getShortName();
    }
    public function addRatingItem(RatingItem $entity) {
        if(!empty($entity)){
            $db = Yii::$app->db;
            $db->createCommand()->insert($this->tableName, get_object_vars($entity))->execute();
        } else {
            throw new InvalidArgumentException();
        }  
    }

    public function deleteRatingItemsByPage(int $pageId) {
        if($pageId != null){
            $db = Yii::$app->db;
            $command = $db->createCommand();
            $command->delete($this->tableName, "pageId = {$pageId}");
            $command->execute();
        } else {
            throw new InvalidArgumentException();
        }  
    }

    public function getAverageRatingOfPage(int $pageId) {
       if($pageId != null){
            $db = Yii::$app->db;
            $command = $db->createCommand('SELECT AVG(rating) FROM '.$this->tableName.' WHERE pageId=:pageId');
            $query = $command->bindValue(":pageId", $pageId)
                    ->queryOne();
            return $query;
        } else {
            throw new InvalidArgumentException();
        } 
    }

    public function getRatingItem(int $pageId, string $ipAddress) {
        if($pageId != null){
            $db = Yii::$app->db;
            $command = $db->createCommand('SELECT rating FROM '.$this->tableName.' WHERE pageId=:pageId AND ipAddress=:ipAddress');
            $query = $command->bindValue(":pageId", $pageId)
                    ->bindValue(":ipAddress", $ipAddress)
                    ->queryOne();
            return $query;
        } else {
            throw new InvalidArgumentException();
        } 
    }

}
