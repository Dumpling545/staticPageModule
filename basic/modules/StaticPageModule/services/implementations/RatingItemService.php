<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\services\implementations;

use app\modules\StaticPageModule\services\abstractions\IRatingItemService;
use app\modules\StaticPageModule\repositories\implementations\RatingItemRepository;
use app\modules\StaticPageModule\repositories\entities\RatingItem;

class RatingItemService implements IRatingItemService{
    private $ratingRepository;
    public function __construct() {
        $this->ratingRepository = new RatingItemRepository();
    }

    public function addRatingItem(int $pageId, int $rating, string $ipAddress) {
        $entity = new RatingItem();
        $entity->ipAddress = $ipAddress;
        $entity->pageId = $pageId;
        $entity->rating = $rating;
        try{
            $this->ratingRepository->addRatingItem($entity);
        } catch(\Exception $e){
            throw $e;
        }
    }

    public function deleteRatingItemsByPage(int $id) {
        try{
            $this->ratingRepository->deleteRatingItemsByPage($id);
        } catch(\Exception $e){
            throw $e;
        }
    }

    public function getAverageRatingOfPage($pageId) {
        try{
            if($pageId != null)
                return $this->ratingRepository->getAverageRatingOfPage($pageId)['AVG(rating)'];
        } catch(\Exception $e){
            throw $e;
        }
    }

    public function getRatingItem($pageId, string $ipAddress) {
        try{
            return $this->ratingRepository->getRatingItem($pageId, $ipAddress)['rating'];
        } catch(\Exception $e){
            return -1;
        }
    }

}
