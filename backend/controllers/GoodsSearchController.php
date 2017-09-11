<?php

namespace backend\controllers;

use backend\models\Search;

class GoodsSearchController extends \yii\web\Controller
{
    public function actionSearch(){
        $search=new Search();
        $rows=$search->getSearch();
        return $this->render('index',['rows'=>$rows,'search'=>$search]);
    }
}
