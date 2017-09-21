<?php
namespace backend\components;
use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter{
    public function beforeAction($action)
    {
        $user=\Yii::$app->user;
        $auth=\Yii::$app->authManager;
        //如果没有设定的权限的就是公共(没有在权限管理),直接通过
        if(!$auth->getPermission($action->uniqueId)){
            return true;
        }
        if($user->can($action->uniqueId)){//action的uniquyeId就是当前的routeId
            return parent::beforeAction($action);
        }
        else{
            if($user->isGuest){
                //这里跳转需要执行send,确保跳转,而不是返回redirect的结果
                return $action->controller->redirect($user->loginUrl)->send();
            }
            throw new HttpException(403,'你没有权限访问');
        }
    }
}