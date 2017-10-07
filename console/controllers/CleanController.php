<?php
namespace console\controllers;
use frontend\models\Order;
use yii\console\Controller;

class CleanController extends Controller {
    public function actionClean(){
        while(1){
            $where='create_time<'.strtotime('-1 day').' and status=1';
            Order::updateAll(['status'=>0],$where);
            sleep(2);
            echo '====clean====\n';
        }
    }
}