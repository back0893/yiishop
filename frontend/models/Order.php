<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property string $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property string $create_time
 */
class Order extends \yii\db\ActiveRecord
{

    public static $sendWay=[
      1=>['name'=>'普通快递','price'=>20,'intro'=>'运费20.00元'],
      2=>['name'=>'特快快递','price'=>40,'intro'=>'运费40.00元'],
      3=>['name'=>'一日达','price'=>70,'intro'=>'运费70.00元']
    ];
    public static $payWay=[
      1=>['name'=>'在线支付','intro'=>'网上支付,支持银行卡,支付宝,微信'],
      2=>['name'=>'货到付款','intro'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
      3=>['name'=>'他人付款','intro'=>'他人帮忙支付'],
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'delivery_id', 'payment_id', 'status'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['province', 'city', 'area'], 'string', 'max' => 20],
            [['address', 'delivery_name', 'payment_name', 'trade_no', 'create_time'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
        ];
    }
    public function loadAddress($id){
        $address=Address::findOne(['id'=>$id,'user_id'=>Yii::$app->user->id]);
        $this->name=$address->name;
        $this->province=$address->getAddress('province')->name;
        $this->city=$address->getAddress('city')->name;
        $this->area=$address->getAddress('town')->name;
        $this->address=$address->address;
        $this->tel=$address->tel;
    }
    public function loadDelivery($id){
        $this->delivery_id=$id;
        $this->delivery_name=self::$sendWay[$id]['name'];
        $this->delivery_price=self::$sendWay[$id]['price'];
    }
    public function loadPayment($id){
        $this->payment_id=$id;
        $this->payment_name=self::$payWay[$id]['name'];
    }
    public static function getStatus(){
        $query=Yii::$app->db->createCommand('select status,count(*) as `count` from `order` group by status');
        return ArrayHelper::map($query->queryAll(),'status','count');
    }
    public function getOrderGoods(){
        return $this->hasOne(OrderGoods::className(),['order_id'=>'id'])->all();
    }
}
