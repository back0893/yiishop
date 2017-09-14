<?php
namespace backend\models;

use yii\base\Model;

class Permission extends Model{
    public $name;
    public $desc;
    public function rules()
    {
        return [
            ['name','required'],
            ['desc','string']
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'权限名称',
            'desc'=>'描述'
        ];
    }
}