<?php
namespace backend\models;

use yii\base\Model;

class Role extends Model{
    public $name;
    public $desc;
    public $permissions;

    public function rules()
    {
        return [
            [['name','desc'],'required'],
            ['permissions','each','rule'=>['string']]
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'desc'=>'描述',
            'permissions'=>'权限'
        ];
    }
}