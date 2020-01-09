<?php
namespace common\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;

class PageQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}