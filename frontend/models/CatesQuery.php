<?php
namespace frontend\models;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class CatesQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}