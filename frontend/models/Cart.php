<?php

namespace app\models;

use frontend\models\Goods;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;

/**
 * This is the model class for table "cart".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $amount
 * @property integer $member_id
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'amount', 'member_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品id',
            'amount' => '商品数量',
            'member_id' => '用户id',
        ];
    }
    //增加,在增加是需要判断是否存在
    public static function addGoods($goods_id,$amount){
        $amount=($amount>0)?$amount:1;
        if(\Yii::$app->user->isGuest){
            $value=unserialize(Yii::$app->request->cookies->getValue('carts',''));
            $value=$value?$value:[];
            if(array_key_exists($goods_id,$value)){
                $value[$goods_id]+=$amount;
            }else{
                $value[$goods_id]=$amount;
            }
            $cookie=new Cookie([
                'name'=>'carts',
                'value'=>serialize($value)
            ]);
            Yii::$app->response->cookies->add($cookie);
        }else{
            if($cart=self::findOne(['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id])){
                $cart->amount+=$amount;
            }else{
                $cart=new self();
                $cart->amount=$amount;
                $cart->goods_id=$goods_id;
                $cart->member_id=Yii::$app->user->id;
            }
            $cart->save(false);
        }
    }
    //获得商品商品
    public static function  getGoods(){
        if(Yii::$app->user->isGuest){
            $value=unserialize(Yii::$app->request->cookies->getValue('carts',''));
            $value=$value?$value:[];
            $ids=array_keys($value);
            return [Goods::find()->where(['in','id',$ids])->all(),$value];
        }else{
            $goods=self::find()->select(['goods_id','amount'])->where(['member_id'=>Yii::$app->user->id])->all();
            $goods=ArrayHelper::map($goods,'goods_id','amount');
            $ids=array_keys($goods);
            return [Goods::find()->where(['in','id',$ids])->all(),$goods];
        }
    }
    //修改,主要是增加和减少,最少为1
    public static function editGoods($goods_id,$amount){
        $amount=($amount>0)?$amount:1;
        if(Yii::$app->user->isGuest){
            $cookie=Yii::$app->request->cookies->get('carts');
            $value=unserialize(Yii::$app->request->cookies->getValue('carts',''));
            $value=$value?$value:[];
            if(key_exists($goods_id,$value)){
                $value[$goods_id]=$amount;
            }
            $cookie->value=serialize($value);
            Yii::$app->response->cookies->add($cookie);
        }else{
            $goods=self::findOne(['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id]);
            $goods->amount=$amount;
            $goods->save(false);
        }
    }
    //删除
    public static function delGoods($goods_id){
        if(Yii::$app->user->isGuest){
            $cookie=Yii::$app->request->cookies->get('carts');
            $cookie->value=$cookie->value?$cookie->value:'';
            $value=unserialize($cookie->value);
            unset($value[$goods_id]);
            $cookie->value=serialize($value);
            Yii::$app->response->cookies->add($cookie);
        }else{
            self::find()->where(['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id])->one()->delete();
        }
    }
    //在登录的时候将cookie中的数据同步到数据表中
    public static function cookie2db(){
        $value=Yii::$app->request->cookies->getValue('carts','');
        if($value){
            //获取cookie,写入数据表
            $value=unserialize($value);
            foreach ($value as $goods_id=>$amount){
                if($cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id])){
                    $cart->amount+=$amount;
                }else{
                    $cart=new Cart();
                    $cart->member_id=Yii::$app->user->id;
                    $cart->amount=$amount;
                    $cart->goods_id=$goods_id;
                }
                $cart->save();
            }
            //在同步完成后删除cookie
            $cookie=new Cookie(['name'=>'carts','value'=>'','expire'=>0]);
            Yii::$app->response->cookies->add($cookie);
        }
    }
}
