<?php
namespace frontend\models;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

class Menu extends ActiveRecord{
    public function rules()
    {
        return [
            [['name','parent_id'],'required'],
            ['parent_id','integer']
        ];
    }

    public function behaviors()
    {
        return [
            'tree'=>[
                'class'=>NestedSetsBehavior::className(),
                'treeAttribute'=>'tree',
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT=>self::OP_ALL,
        ];
    }

    public static function find(){
        return new MenuQuery(get_called_class());
    }
}