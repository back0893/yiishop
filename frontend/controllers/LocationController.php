<?php

namespace frontend\controllers;

use app\models\Address;
use app\models\Locations;
use frontend\components\SmsSendComponent;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class LocationController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAddress(){
        $request=\Yii::$app->request;
        if($request->isPost){
            if($id=$request->post('id')){
                $model=Address::findOne(['id'=>$id]);
            }else{
                $model=new Address();
            }
            if($model->load($request->post(),'') && $model->validate()){
                $model->save();
                return $this->redirect(['address']);
            }
            var_dump($model->errors);exit;
        }
        $addresses=Address::find()->where(['user_id'=>\Yii::$app->user->identity->id])->all();
        return $this->render('address',['addresses'=>$addresses]);
    }
    public function actionGetinfo($parend_id){
        $rows=Locations::find()->where(['parent_id'=>$parend_id])->asArray()->all();
        return json_encode($rows);
    }
    public function actionDefault($id){
        $address=Address::findOne(['id'=>$id]);
        $tr1=\Yii::$app->db->beginTransaction();
        try{
            $address->status=1;
            $address->save(false);
            $tr1->commit();
            return json_encode(['error'=>0]);
        }catch (\Exception $e){
            $tr1->rollBack();
            return json_encode(['error'=>1]);
        }
    }
    public function actionDel($id){
        $address=Address::findOne(['id'=>$id]);
        $tr1=\Yii::$app->db->beginTransaction();
        try{
            $address->delete();
            $tr1->commit();
            return json_encode(['error'=>0]);
        }catch (\Exception $e){
            $tr1->rollBack();
            return json_encode(['error'=>1]);
        }
    }
    public function behaviors()
    {
        return [
            'filter'=>[
                'class'=>AccessControl::className(),
                'except'=>['test','sms'],
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=>['@']
                    ]
                ]
            ],
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'send-sms'=>['post'],
                    'check-sms'=>['post']
                ]
            ]
        ];
    }
    public function actionEdit($id){
        $address=Address::find()->where(['id'=>$id])->asArray()->one();
        if($address){
            $error=0;
        }else{
            $error=1;
        }
        return json_encode(['error'=>$error,'address'=>$address]);
    }
    public function actionSendSms(){
        $tel=\Yii::$app->request->post('tel','');
        if(empty($tel)){
            return json_encode(['error'=>1,'info'=>'非法手机号']);
        }
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        //先检查这个手机号是否是发送过的,没有发送过才会去发送短信
        $code=$redis->get($tel);
        if(!$code){
            //发送短信,开始
//            $code=$this->sendSms();
            //发送短信结束
            //测试返回code,
            $code=1111;
        }
        if(!$code){
            return json_encode(['error'=>1,'info'=>'手机号无法接受短信']);
        }
        $redis->set($tel,$code,60*30);
        return json_encode(['error'=>0,'info'=>'']);
    }
    public function actionCheckSms(){
        $tel=\Yii::$app->request->post('tel','');
        $postCode=\Yii::$app->request->post('code','');
        if(empty($tel)){
            return 'false';
        }
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $saveCode=$redis->get($tel);
        if($saveCode!=$postCode){
            return 'false';
        }
        return 'true';
    }
    protected function sendSms(){
        $ak=\Yii::$app->params['sms']['ak'];
        $sk=\Yii::$app->params['sms']['sk'];
        $deam=new SmsSendComponent($ak,$sk);
        $code=mt_rand(1000,9999);
        $response=$deam->sendSms(
            '刘国君',
            'SMS_97325002',
            '18328692764',
            ['code'=>$code]
        );
        if(strtolower($response->Code)=='ok'){
            return $code;
        }
        return false;
    }
    public function actionTest(){
        $ak=\Yii::$app->params['sms']['ak'];
        $sk=\Yii::$app->params['sms']['sk'];
        $deam=new SmsSendComponent($ak,$sk);
        $response=$deam->queryDetails("18328692764",
    '20170919',
        10,
        1
        );
        header('Content-Type: text/plain; charset=utf-8');
        $response=json_decode(json_encode($response),true);
        print_r($response);
    }
}
