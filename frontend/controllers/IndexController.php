<?php

namespace frontend\controllers;

use backend\models\SphinxClient;
use Endroid\QrCode\QrCode;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;
use frontend\models\Goods;
use frontend\models\GoodCates;
use GuzzleHttp\Client;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;


class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $Path=\Yii::getAlias('@app');
        if(!file_exists($Path.'/web/html/index.html')){
            $content=$this->render('index');
            file_put_contents($Path.'/web/html/index.html',$content);
        }
        echo file_get_contents($Path.'/web/html/index.html');exit;
    }
    public function actionTest(){
        $p=GoodCates::generateNodes();
        var_dump($p);
    }
    public function actionList(){
        $request=\Yii::$app->request;
        $cates=$request->get('cates',1);
        $keyWord=$request->get('keyWord',null);
//        $cl=new SphinxClient();
//        $cl->SetServer ( '127.0.0.1', 9312);
//        $cl->SetConnectTimeout ( 10 );
//        $cl->SetArrayResult ( true );
//        // $cl->SetMatchMode ( SPH_MATCH_ANY);
//        $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);
//        $cl->SetLimits(0, 100);
//        $res = $cl->Query($keyWord, 'goods');//shopstore_search
////print_r($cl);
//        if(isset($res['matches'])){
//            $goodsId=ArrayHelper::getColumn($res['matches'],'id');
//        }
//        $goodsId=[];
        $keyWords=$this->Jieba($keyWord);
        $ids=GoodCates::getChildrenId($cates);
        list($goods,$paginate)=Goods::getGoods($ids,$keyWords);
        return $this->render('list',['goods'=>$goods,'paginate'=>$paginate,'keyWord'=>$keyWord]);
    }
    public function actionGoods($id){
        $filePath=\Yii::getAlias("@frontend/web/html/{$id}.html");
        if(!file_exists($filePath)){
            $goods=Goods::findOne(['id'=>$id]);
            $imgs=ArrayHelper::getColumn($goods->getImages(),'path');
            $content=$this->render('goods',['goods'=>$goods,'imgs'=>$imgs]);
            file_put_contents($filePath,$content);
        }
        echo  file_get_contents($filePath);exit;
    }
    public function CatBd()
    {
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        if($catbd=$redis->get('catbd')){
            return $catbd;
        }
        $catbd=$this->renderPartial('catbd');
        $redis->set('catbd',$catbd,3600);
        return $catbd;
    }
    public function actionSendEmail(){
        $mail=\Yii::$app->mailer->compose();
        $result=$mail->setTo('back0893@163.com')
            ->setFrom('back0893@163.com')
            ->setSubject('邮箱发送测试')
            ->setHtmlBody('这是一个php的邮箱发送测试')
            ->send();
        var_dump($result);
    }
    public function actionViewCount($id){
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $count=$redis->incr('goods_'.$id);
        if(!($count%10)){
            Goods::updateAllCounters(['view'=>$count],['id'=>$id]);
        }
        return json_encode(['view'=>$count]);
    }
    public function actionTest1(){
        $app=new Application(\Yii::$app->params['weChat']);
        $payment=$app->payment;
        $trade_no='125125';
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('trade_no',$trade_no);
        $attributes = [
            'trade_type'       => 'NATIVE', // JSAPI，NATIVE，APP... 扫码支付必须是NATIVE
            'body'             => '京西商城订单',//商品描述
            'detail'           => '小米6,ipone8等',//商品详情
            'out_trade_no'     => $trade_no,
            'total_fee'        => 1, // 单位：分
            'notify_url'       => 'http://klinoe.ngrok.cc/callBack.php', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            //'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order=new Order($attributes);
        //记住这里调用的方法不相同,使用微信的统一下单是prepare
        $result=$payment->prepare($order);
        if($result->return_code=='SUCCESS' && $result->result_code=='SUCCESS'){
            $code_url=$result->code_url;
            $prepay_id=$result->prepay_id;
            $redis->set('code_url',$code_url);
            $redis->set('prepay_id',$prepay_id);
            file_put_contents('url.txt',$code_url);
            $qrCode=new QrCode($code_url);
            $qrCode->setSize(300);
            header('Content-Type: '.$qrCode->getContentType());
            echo $qrCode->writeString();exit;
        }
        var_dump($result);
    }
    public function actionTest2(){
        $app=new Application(\Yii::$app->params['weChat']);
        $payment=$app->payment;
        $query=$payment->query('125125');
        if($query->return_code=='SUCCESS' && $query->trade_state=='USERPAYING'){
            var_dump($query->out_trade_no);
        }
        var_dump($query);
    }
    public function actionTest3(){
        $app=new Application(\Yii::$app->params['weChat']);
        $payment=$app->payment;
        $query=$payment->close('125125');
        if($query->return_code=='SUCCESS' &&$query->result_code=='SUCCESS'){
            var_dump('订单关闭成功');exit;
        }
        var_dump('关闭订单失败');
    }

    /**
     * 分词工具,去请求使用python建立的分词服务器
     * php的jieba每次请求都需要从硬盘中读取数据,太慢了
     * 使用python的因为python的服务器是常驻的,
     * 一次读取后就保存在内存中,速度快
     * 测试.但是在mysql查询会多使用内存
     * @param $keyWord string 查询关键词
     * @return array 返回解析后的json数据
     */
    protected function Jieba($keyWord){
        $client=new Client([
            'timeout'=>3
        ]);
        $response=$client->get('http://127.0.0.1:9000',['query'=>[
            'keyWord'=>$keyWord
        ]]);
        return json_decode($response->getBody()->getContents(),true);
    }
    public function actionTest4(){
        $client=new Client([
            'timeout'=>3
        ]);
        $reponse=$client->get('http://wthrcdn.etouch.cn/weather_mini',[
            'query'=>[
                'city'=>'成都'
            ]
        ]);
        var_dump(json_decode($reponse->getBody()->getContents()));
    }

}
