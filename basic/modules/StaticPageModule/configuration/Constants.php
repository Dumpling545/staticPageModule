<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\configuration;

/**
 * Description of Constants
 *
 * @author admin
 */
 class Constants {
    const PAGE_ID_KEY = "pageId";
    const CATEGORY_ID_KEY = "categoryId";
    
    const SORT_BY_NEW_DATE_TO_OLD = "newDateToOld";
    const SORT_BY_RATING = "rating";
    const SORT_BY_OLD_DATE_TO_NEW = "oldDateToNew";
    const SORT_BY_DATE_LAST_MODIFIED = "dateLastModified";
    const SORT_BY_HEADER = "header";
    
    const ALLOWED_TO_ALL = 0;
    const ALLOWED_BY_LINK = 1;
    const ALLOWED_TO_AUTHORIZED_USERS = 2;
    const ALLOWED_TO_ADMINS = 3;
    
    const GUEST = 0;
    const AUTHORIZED_USER = 2;
    const ADMIN = 3;
    
    const BAD_REQUEST = 400;
    const FORBIDDEN = 403;
}
