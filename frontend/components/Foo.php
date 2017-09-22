<?php
namespace frontend\components;
use yii\base\Component;
use yii\base\Event;

class Foo extends Component{
    const EVENT_HELLO='hello';
    public function __construct(array $config = [])
    {
        Event::on(self::className(),self::EVENT_HELLO,function(){
            echo'类的最后调用';
        });
        parent::__construct($config);
    }

    public function bar($event){
        $this->trigger(self::EVENT_HELLO,$event);
    }
    public static function op(){
        echo '这是一个foo上静态方法<br>';
    }
    public function behaviors()
    {
        return [
            'mybehaviors'=>[
                'class'=>MyBehaviors::className()
            ]
        ];
    }
    public function be(){
        echo('这是foo中be方法<br>');
    }
}