<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\repositories\abstractions;
use app\modules\StaticPageModule\repositories\entities\RatingItem;
/**
 *
 * @author admin
 */
interface IRatingItemRepository {
    public function addRatingItem(RatingItem $entity);
    public function deleteRatingItemsByPage(int $pageId);
    public function getAverageRatingOfPage(int $pageId);
    public function getRatingItem(int $pageId, string $ipAddress);
}
