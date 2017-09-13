<?php

namespace backend\controllers;

use backend\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;

class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=User::find()->where(['>=','status',0]);
        $paginate=new Pagination([
                'totalCount'=>$query->count(),
                'defaultPageSize'=>3
            ]
        );
        $users=$query->limit($paginate->limit)->offset($paginate->offset)->all();
        return $this->render('index',['users'=>$users,'paginate'=>$paginate]);
    }
    public function actionAdd(){
        $user=new User();
        $request=\Yii::$app->request;
        if($request->isPost){
            $user->scenario='edit';
            if($user->load($request->post()) && $user->validate()){
                $security=\Yii::$app->getSecurity();
                $user->password_hash=$security->generatePasswordHash($user->password_hash);
                //生成一个用于自动登录的令牌
                $user->auth_key=$security->generateRandomString();
                $user->save(false);
                return $this->redirect(['index']);
            }
            var_dump($user->errors,$user->password_hash,$user->rpassword);exit;
        }
        return $this->render('add',['user'=>$user]);
    }
    public function actionEdit($id){
        $user=User::find()->select(['username', 'email','status','password_hash','id'])->where(['id'=>$id])->One();
        if(!$user){
            throw new HttpException(404,'没有这个管理员');
        }
        $request=\Yii::$app->request;
        if($request->isPost){
            $user->scenario='edit';
            if($user->load($request->post()) && $user->validate()){
                $security=\Yii::$app->getSecurity();
                if($user->pwd){
                    $user->password_hash=$security->generatePasswordHash($user->pwd);
                }
                $user->save(false);
                return $this->redirect(['index']);
            }
            var_dump($user->errors);exit;
        }
        return $this->render('add',['user'=>$user]);

    }
    public function actionLogin(){
        if(!\Yii::$app->user->isGuest){
            \Yii::$app->session->setFlash('info','你已经登录了');
            $this->goBack();
        }
        $user=new User();
        $request=\Yii::$app->request;
        if($request->isPost){
            $remember=$request->post('remember');
            $user->scenario='login';
            if($user->load($request->post()) && $user->validate()){
                $Luser=$user::find()->orFilterWhere(['username'=>$user->login])->orFilterWhere(['email'=>$user->login])->One();
                if($Luser->status==-1){
                    throw new HttpException(403,'该帐号被禁止登录');
                }
                $security=\Yii::$app->getSecurity();
                if($security->validatePassword($user->pwd,$Luser->password_hash)){
                    $Luser->touch('last_login_time');
                    $Luser->last_login_ip=\Yii::$app->request->getUserIP();
                    $Luser->save();
                    \Yii::$app->user->login($Luser,$remember?60*60:0);
                    $this->goBack();
                }
            }
            var_dump($user->errors);
        }
        return $this->render('login',['user'=>$user]);
    }
    public function actionLogout(){
        if(\Yii::$app->user->isGuest){
            $this->goBack();
        }
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('info','退出成功');
        $this->goHome();
    }
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'allow'=>true,
                        'actions'=>['login'],
                        'roles'=>['?']
                    ],
                    [
                        'allow'=>true,
                        'actions'=>['logout'],
                        'roles'=>['@']
                    ],[
                        'allow'=>true,
                        'roles'=>['@']
                    ]
                ]

            ]
        ];
    }
    public function actionDel($id){
        $user=User::find()->where(['id'=>$id])->One();
        if(!$user){
            throw new HttpException(404,'没有这个管理员');
        }
        $user->status=-1;
        $user->save();
        return $this->redirect(['index']);
    }
    public function actionTest(){
        var_dump(\Yii::$app->user->isGuest,\Yii::$app->user->id);
        $cookie=\Yii::$app->request->cookies;
        var_dump($cookie);
    }
}
