<?php
namespace backend\controllers;
use backend\models\Tree;
use yii\web\Controller;

class TreeController extends Controller{
    public function actionIndex(){
        return $this->render('index');
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
    public function actionAdd(){
        $model=new Tree();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            var_dump($model->intro);exit;
        }
        return $this->render('add',['model'=>$model]);
    }
}