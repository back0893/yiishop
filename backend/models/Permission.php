<?php
namespace backend\models;

use yii\base\Model;

class Permission extends Model{
    public $name;
    public $desc;
    public function rules()
    {
        return [
            [['name','desc'],'required'],
            ['name','validateNameUnique']
        ];
    }
    public function save(){
        $auth=\Yii::$app->authManager;
        $permission = $auth->createPermission($this->name);
        $permission->description = $this->desc;
        return $auth->add($permission);
    }
    public function validateNameUnique(){
        $auth=\Yii::$app->authManager;
        $selfName=\Yii::$app->request->get('name','');
        if($this->name!=$selfName &&$auth->getPermission($this->name)){
            $this->addError('name','权限已经存在');
        }
    }
    public function update($permission,$oldname){
        $auth=\Yii::$app->authManager;
        $permission->name=$this->name;
        $permission->description=$this->desc;
        $auth->update($oldname,$permission);
    }
    public function attributeLabels()
    {
        return [
            'name'=>'权限名称',
            'desc'=>'描述'
        ];
    }
}