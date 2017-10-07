<?php

namespace backend\controllers;

use backend\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\ArrayHelper;
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
        $auth=\Yii::$app->authManager;
        if($request->isPost){
            //声明使用情景
            $user->scenario='add';
            if($user->load($request->post()) && $user->validate()){
                $user->save(false);
                return $this->redirect(['index']);
            }
            var_dump($user->errors,$user->password_hash,$user->rpassword);exit;
        }
        $roles=ArrayHelper::map($auth->getRoles(),'name','description');
        return $this->render('add',['user'=>$user,'roles'=>$roles]);
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
//                var_dump($user->roles);exit;
                $user->save(false);
                return $this->redirect(['index']);
            }
            var_dump($user->errors);exit;
        }
        $auth=\Yii::$app->authManager;
        $roles=ArrayHelper::map($auth->getRoles(),'name','description');
        //获取用户本身的角色
        $user->roles=ArrayHelper::getColumn($auth->getRolesByUser($user->id),'name');
        return $this->render('add',['user'=>$user,'roles'=>$roles]);

    }
    public function actionLogin(){
        if(!\Yii::$app->user->isGuest){
            \Yii::$app->session->setFlash('info','你已经登录了');
            $this->goBack();
        }
        $user=new User();
        $request=\Yii::$app->request;
        if($request->isPost){
            $remember=$request->post('remember',false);
            $user->scenario='login';
            if($user->load($request->post()) && $user->validate()){
                $Luser=User::find()->orFilterWhere(['username'=>$user->login])->orFilterWhere(['email'=>$user->login])->One();
                if($Luser->status==-1){
                    throw new HttpException(403,'该帐号被禁止登录');
                }
                if($Luser->validateLogin($user)){
                    \Yii::$app->user->login($Luser,$remember?60*60:0);
                    return $this->redirect(['index']);
                }
                $user->addError('pwd','密码错误');
            }
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
                    ],
                    [
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
        var_dump(\Yii::getAlias('@frontend/web/html/1.html'));
    }
    public function actionChangePassword(){
        /**
         * @var $identity User
         */
        $identity=\Yii::$app->user->identity;
        $identity->scenario=$identity::SCENARIO_CHANGE_PASSWORD;
        $request=\Yii::$app->request;
        if($request->isPost && $identity->load($request->post()) && $identity->validate()){
            {
                $identity->save(false);
                \Yii::$app->user->logout();
                \Yii::$app->session->setFlash('success','修改密码成功,请重新登录');
                return $this->redirect('login');
            }
        }
        return $this->render('change_password',['identity'=>$identity]);
    }
}
