<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\models\ratingItem;

use yii\base\Model;
class AddRatingItemModel extends Model{
    public $rating;
    public $pageId;
    public function rules()
    {
        return [
            [['pageId', 'rating'], 'required'],
            [['pageId', 'rating'], 'string']
        ];
    }
}
