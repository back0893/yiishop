<?php

namespace frontend\controllers;

use frontend\models\Goods;
use frontend\models\GoodCates;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionTest(){
        $p=GoodCates::generateNodes();
        var_dump($p);
    }
    public function actionList($cates){
        $ids=GoodCates::getChildrenId($cates);
        $goods=Goods::getGoods($ids);
        return $this->render('list',['goods'=>$goods]);
    }
    public function actionGoods($id){
        $goods=Goods::findOne(['id'=>$id]);
        $imgs=ArrayHelper::getColumn($goods->getImages(),'path');
        return $this->render('goods',['goods'=>$goods,'imgs'=>$imgs]);
    }
}
