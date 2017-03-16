<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\services\abstractions;
/**
 *
 * @author admin
 */
interface IRatingItemService {
    function addRatingItem(int $pageId, int $rating, string $ipAddress);
    function deleteRatingItemsByPage(int $id);
    function getAverageRatingOfPage($model);
    function getRatingItem($pageId, string $ipAddress);
}
