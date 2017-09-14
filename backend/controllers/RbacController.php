<?php
namespace backend\controllers;

use backend\models\Role;
use backend\models\User;
use backend\rbac\AdminRule;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use backend\models\Permission;
use yii\web\HttpException;

//权限实际上只是一个标识,没有任何的功能呢,验证权限,实际上是验证角色是否含有这个标识
//权限的验证实际是去寻找是否有每个权限名,或者用户名,如果存在就表示有这个权限反之就没有这个权限

class RbacController extends Controller
{
    public function actionIndexPermission(){
        $auth=\Yii::$app->authManager;
        $permissions=array_values($auth->getPermissions());
        return $this->render('indexPermission',['permissions'=>$permissions]);
    }
    public function actionCreatePermission()
    {
        //创建接受数据的模型
        $model = new Permission();
        //获得权限管理模型
        $auth = \Yii::$app->authManager;
        //添加想要的权限名称
        $request = \Yii::$app->request;
        if ($model->load($request->post()) && $model->validate()) {
            if($auth->getPermission($model->name)){
                $model->addError('name','权限已经存在');
            }else{
                $permission = $auth->createPermission($model->name);
                $permission->description = $model->desc;
                $auth->add($permission);
                \Yii::$app->session->setFlash('success', '添加权限' . $model->name . '成功');
                return $this->redirect(['index-permission']);
            }
        }
        return $this->render('createPermission', ['model' => $model]);
    }
    public function actionEditPermission($name){
        $auth = \Yii::$app->authManager;
        $permission=$auth->getPermission($name);
        if(!$permission){
            throw new HttpException(404,'没有这个权限:'.$name);
        }
        $model = new Permission();
        $request = \Yii::$app->request;
        if($request->isPost && $model->load($request->post())){
            $permission->description=$model->desc;
            $auth->update($permission->name,$permission);
            return $this->redirect(['rbac/index-permission']);
        }
        $model->desc=$permission->description;
        $model->name=$permission->name;
        return $this->render('createPermission',['model'=>$model]);
    }

    public function actionIndexRole(){
        $auth=\Yii::$app->authManager;
        $roles=array_values($auth->getRoles());
        return $this->render('indexRole',['roles'=>$roles]);
    }
    public function actionCreateRole()
    {
        $model = new Role();
        $auth = \Yii::$app->authManager;
        $request = \Yii::$app->request;
        if ($model->load($request->post()) && $model->validate()) {
            if($auth->getRole($model->name)){
                $model->addError('name','角色已经存在');
            }else{
                $role = $auth->createRole($model->name);
                $role->description = $model->desc;
                $auth->add($role);
                foreach ($model->permissions as $permission){
                    $auth->addChild($role,$auth->getPermission($permission));
                }
                \Yii::$app->session->setFlash('success', '添加角色' . $model->name . '成功');
                return $this->redirect(['index']);
            }
        }
        $temp=$auth->getPermissions();
        $permissions=ArrayHelper::map($temp,'name','description');
        return $this->render('createRole', ['model' => $model,'permissions'=>$permissions]);
    }
    public function actionEditRole($name)
    {
        $auth = \Yii::$app->authManager;
        $role=$auth->getRole($name);
        if(!$role){
            throw new HttpException(404,'寻找的角色不存在:'.$name);
        }
        $model = new Role();
        $request = \Yii::$app->request;
        if ($model->load($request->post()) && $model->validate()) {
            $oldname=$role->name;
            $role->name=$model->name;
            $role->description=$model->desc;
            //这里需要先获得旧的name,以旧的name为条件来修改
            $auth->update($oldname,$role);
            //先解除原有的角色对应的权限
            $auth->removeChildren($role);
            //解除完权限后,在增添权限
            foreach ($model->permissions as $permission){
                $auth->addChild($role,$auth->getPermission($permission));
            }

            \Yii::$app->session->setFlash('success', '修改角色' . $model->name . '成功');
            return $this->redirect(['index-role']);
        }
        $temp=$auth->getPermissions();
        $model->name=$role->name;
        $model->desc=$role->description;
        $permissions=ArrayHelper::map($auth->getPermissionsByRole($role->name),'name','description');
        $model->permissions=array_keys($permissions);
//        var_dump($model->permissions);exit;
        return $this->render('createRole', ['model' => $model,'permissions'=>ArrayHelper::map($temp,'name','description')]);
    }
    public function actionTest(){
        $auth=\Yii::$app->authManager;
//        $temp=$auth->getPermissions();
//        $permissions=ArrayHelper::map($temp,'name','description');
//        var_dump($auth->getRoles());
//        var_dump($auth->getRoles());
        $r=$auth->getRole('test1');
//        $auth->removeAllRoles();
//        $auth->assign($r,1);
        $u=User::findOne(['id'=>1]);
        \Yii::$app->user->login($u);
        var_dump(\Yii::$app->user->can('user/edit'));
        var_dump(\Yii::$app->user->can('user/add'));
        var_dump(\Yii::$app->user->can('user/del'));
        //获得在控制器中启动的action的id
        var_dump(\Yii::$app->user->can($this->action->id));
    }
}