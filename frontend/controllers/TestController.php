<?php
namespace frontend\controllers;
use frontend\components\Foo;
use yii\web\Controller;
use yii\base\Event;

class TestController extends Controller{
    public function actionTest(){
        $foo=new Foo();
        $foo->on(Foo::EVENT_HELLO,[$this,'testEven']);
//        $foo->on(Foo::EVENT_HELLO,'Extra');
        $foo->on(Foo::EVENT_HELLO,function($event){
           echo  '这是匿名方法<br>';
           echo '额外传递数据<br>';
           echo $event->message;
           var_dump($event->data);
        },[1,'abc']);
        $foo->on(FOO::EVENT_HELLO,['frontend\components\Foo','op']);
        $event=new MessageEvent();
        $event->message='这是传递的事件event<br>';
        //会将event上的属性传递给触发事件
//        $foo->trigger(Foo::EVENT_HELLO,$event);
        $foo->bar($event);
    }
    public function testEven(){
        echo '这是test控制器中的附加事件<br>';
    }
}
//function Extra(){
//    echo '这是额外的普通方法<br>';
//}
class MessageEvent extends Event{
    public $message;
}