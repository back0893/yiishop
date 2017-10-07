<?php

namespace frontend\controllers;

use app\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\base\Exception;
use yii\filters\AccessControl;
use frontend\models\Address;

class CartController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAdd($goods_id,$amount){
        Cart::addGoods($goods_id,$amount);
        return $this->redirect(['cart/show'],301);
    }
    public function actionShow(){
        list($goodss,$cookies)=Cart::getGoods();
        return $this->render('carts',['goodss'=>$goodss,'cookies'=>$cookies]);
    }
    public function actionEdit(){
        $request=\Yii::$app->request;
        $goods_id=$request->post('goods_id',0);
        $amount=$request->post('amount',0);
        Cart::editGoods($goods_id,$amount);
    }
    public function actionDel(){
        $request=\Yii::$app->request;
        $goods_id=$request->post('goods_id',0);
        Cart::delGoods($goods_id);
    }
    public function actionEntryOrder(){
        $request=\Yii::$app->request;
        list($goodss,$cookies)=Cart::getGoods();
        if($request->isPost){
            $order=new Order();
            $order->member_id=\Yii::$app->user->id;
            //加载收货人,地址
            $order->loadAddress($request->post('address',0));
            //加载送货方式
            $order->loadDelivery($request->post('delivery',0));
            //加载付款方式
            $order->loadPayment($request->post('pay',0));
            $order->create_time=time();
            $order->status=1;
            $order->total=0;
            //开始事物
            $tr=\Yii::$app->db->beginTransaction();
            //先保存,以获取订单的id,总金额可以在写入订单时候更新
            $order->save(false);
            //将商品的详情写入详情表,确认是否可以发货
            try{
                foreach ($goodss as $i=>$goods){
                    if($goods->stock<$cookies[$goods->id]){
                        throw new Exception('库存不足');
                    }
                    $order_goods=new OrderGoods();
                    $order_goods->MySave($order,$goods,$cookies);
                    //商品减少库存
                    $goods->stock-=$order_goods->amount;
                    $goods->save();
                    //删除购物车商品
                    Cart::delGoods($goods->id);
                }
            }catch (Exception $e){
                $tr->rollBack();
                var_dump($e->getMessage());exit;
            }
            $order->save(false);
            $tr->commit();
            return $this->render('success');
        }
        $addresses=Address::find()->where(['user_id'=>\Yii::$app->user->id])->all();
        return $this->render('entryOrder',['goodss'=>$goodss,'addresses'=>$addresses,'cookies'=>$cookies]);
    }
    public function actionOrderInfo(){
        $queries=Order::getStatus();
        $orders=Order::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        return $this->render('order',['orders'=>$orders,'queries'=>$queries]);
    }
    public function behaviors()
    {
        return [
            'access'=>[
              'class'=>AccessControl::className(),
              'only'=>['entry-order','order-info'],
              'rules'=>[
                  [
                      'allow'=>true,
                      'actions'=>['entry-order','order-info'],
                      'roles'=>['@']
                  ]
              ]
            ],
        ];
    }
}
