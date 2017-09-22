<?php
namespace frontend\components;
use yii\base\Behavior;

class MyBehaviors extends Behavior{
    public $pro1;
    protected $pro2;
    public function getPro2(){
        return $this->pro2;
    }
    public function setPro2($value){
        $this->pro2=$value;
    }
    public function getOwner(){
        $owner=$this->owner;
        $this->owner->trigger($owner::EVENT_HELLO);
    }
    public function events()
    {
        return [
            //这里只会有一个事件生效
            Foo::EVENT_HELLO=>'myHello',
            Foo::EVENT_HELLO=>[$this->owner,'be']
        ];
    }
    public function myHello(){
        echo ('这个mybehaviors中的myhello方法<br>');
    }
}