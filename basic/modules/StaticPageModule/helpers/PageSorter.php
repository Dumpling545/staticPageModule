<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\helpers;

use app\modules\StaticPageModule\models\staticPage\PageItem;
use app\modules\StaticPageModule\configuration\Constants;
class PageSorter {
    static function sort(array &$pages, $sortBy){
        if($sortBy!=null && is_string($sortBy)){
            switch ($sortBy):
                case Constants::SORT_BY_NEW_DATE_TO_OLD:
                    usort ($pages, array("app\modules\StaticPageModule\helpers\PageSorter", "compareItemsByNewDateToOld"));
                    break;
                case Constants::SORT_BY_OLD_DATE_TO_NEW:
                    usort ($pages, array("app\modules\StaticPageModule\helpers\PageSorter", "compareItemsByOldDateToNew"));
                    break;
                case Constants::SORT_BY_RATING:
                    usort ($pages, array("app\modules\StaticPageModule\helpers\PageSorter", "compareItemsByRating"));
                    break;
                case Constants::SORT_BY_DATE_LAST_MODIFIED:
                    usort ($pages, array("app\modules\StaticPageModule\helpers\PageSorter", "compareItemsByDateLastModified"));
                    break;
                case Constants::SORT_BY_HEADER:
                    usort ($pages, array("app\modules\StaticPageModule\helpers\PageSorter", "compareItemsByHeader"));
                    break;
                default: break;
            endswitch;
        }
    }
    private static function compareItemsByHeader (PageItem $a, PageItem $b){
        return (strnatcasecmp($a->header, $b->header) > 0);
    }
    private static function compareItemsByNewDateToOld (PageItem $a, PageItem $b){
        return (strtotime($a->dateCreated) - strtotime($b->dateCreated)) < 0;
    }
    private static function compareItemsByOldDateToNew (PageItem $a, PageItem $b){
        return !PageSorter::compareItemsByNewDateToOld($a,$b);
    }
    private static function compareItemsByDateLastModified (PageItem $a, PageItem $b){
        return (strtotime($a->dateLastModified) - strtotime($b->dateLastModified)) < 0;
    }
    private  static function compareItemsByRating (PageItem $a, PageItem $b){
        return $a->rating < $b->rating;
    }
}
