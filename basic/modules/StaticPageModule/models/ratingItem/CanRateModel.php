<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\StaticPageModule\models\ratingItem;

use yii\base\Model;
class CanRateModel extends Model{
    public $canRate;
    public function rules()
    {
        return [
            [['canRate'], 'required'],
            [['canRate'], 'boolean']
        ];
    }
}
