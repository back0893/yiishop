<?php
namespace backend\models;

use yii\base\Model;
use yii\filters\auth\AuthMethod;

class Role extends Model{
    public $name;
    public $desc;
    public $permissions;

    public function rules()
    {
        return [
            [['name','desc'],'required'],
            ['permissions','each','rule'=>['string']],
            ['name','validateNameUnique']

        ];
    }
    public function validateNameUnique(){
        $auth = \Yii::$app->authManager;
        //不能和数据库中其他角色名一样,但是需要排除自己的角色名
        $selfName=\Yii::$app->request->get('name','');
        if($this->name!=$selfName && $auth->getRole($this->name)){
            $this->addError('name','角色已经存在');
        }
    }
    public function save(){
        $auth = \Yii::$app->authManager;
        $role = $auth->createRole($this->name);
        $role->description = $this->desc;
        $auth->add($role);
        $this->addChild($auth,$role);
    }
    public function update($role){
        $oldname=$role->name;
        $role->name=$this->name;
        $role->description=$this->desc;
        $auth = \Yii::$app->authManager;
        //这里需要先获得旧的name,以旧的name为条件来修改
        $auth->update($oldname,$role);
        //先解除原有的角色对应的权限
        $auth->removeChildren($role);
        //解除完权限后,在增添权限
        $this->addChild($auth,$role);
    }
    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'desc'=>'描述',
            'permissions'=>'权限'
        ];
    }

    /**
     * 用来尝试给角色添加子权限,只要添加权限失败就放弃本次添加
     * @param $auth \yii\rbac\ManagerInterface 传入auth管理组件
     * @param $role  \yii\rbac\Role 传入角色实例
     *
     */
    private function addChild($auth,$role){
        $tr1=\Yii::$app->db->beginTransaction();
        try{
            foreach ($this->permissions as $permission){
                $auth->addChild($role,$auth->getPermission($permission));
            }
            $tr1->commit();
        }catch (\Exception $e){
            $tr1->rollBack();
        }
    }
}