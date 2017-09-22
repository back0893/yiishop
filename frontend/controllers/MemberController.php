<?php

namespace frontend\controllers;
use app\models\Cart;
use frontend\models\Member;

class MemberController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionRegister(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $model=new Member();
            $model->scenario=Member::SCENARIO_REGISTER;
            if($model->load($request->post(),'') &&$model->validate()){
                $model->save(false);
                return $this->redirect(['login']);
            }
//            var_dump($model);
            var_dump($model->errors);exit;
        }
        return $this->render('register');
    }
    public function actionValidate(){
        $post=\Yii::$app->request->post();
        $key=array_keys($post)[0];
        $value=$post[$key];
        if(Member::findOne([$key=>$value])){
            return 'false';
        }
        return 'true';
    }
    public function actionLogin(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $model=new Member();
            $model->scenario=Member::SCENARIO_LOGIN;
            if($model->load($request->post(),'') && $model->validate()){
                $login=Member::findOne(['username'=>$model->username]);
                $security=\Yii::$app->security;
                if($security->validatePassword($model->password,$login->password_hash)){
                    \Yii::$app->user->login($login,$model->remember?3600:0);
                    return $this->goBack(['index/index']);
                }
                $model->addError('password','密码错误');
            }
            var_dump($model->errors);exit;
        }
        return $this->render('login');
    }
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }
}
