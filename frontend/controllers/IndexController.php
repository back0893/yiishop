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
    public function actionList(){
        $request=\Yii::$app->request;
        $cates=$request->get('cates',1);
        $keyWord=$request->get('keyWord',null);
        $ids=GoodCates::getChildrenId($cates);
        list($goods,$paginate)=Goods::getGoods($ids,$keyWord);
        return $this->render('list',['goods'=>$goods,'paginate'=>$paginate]);
    }
    public function actionGoods($id){
        $goods=Goods::findOne(['id'=>$id]);
        $imgs=ArrayHelper::getColumn($goods->getImages(),'path');
        return $this->render('goods',['goods'=>$goods,'imgs'=>$imgs]);
    }
}
