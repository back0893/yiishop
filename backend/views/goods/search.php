<?php
$page=\Yii::$app->request->get('page',1);
$searchForm=\yii\bootstrap\ActiveForm::begin(['action'=>\yii\helpers\Url::to(['goods/index','page'=>$page]),'method'=>'GET','layout'=>'inline']);
echo $searchForm->field($search,'name')->textInput(['placeholder'=>'商品名称']);
echo $searchForm->field($search,'sn')->textInput(['placeholder'=>'商品sn']);
echo $searchForm->field($search,'min')->textInput(['placeholder'=>'价格最小值']);
echo $searchForm->field($search,'max')->textInput(['placeholder'=>'价格最大值']);
echo \yii\bootstrap\Html::submitButton('搜索');
\yii\bootstrap\ActiveForm::end();
