<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Brand::find();
        $paginate=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>5
        ]);
        $model=Brand::find()->orderBy('sort DESC')->limit($paginate->limit)->offset($paginate->offset)->all();
        return $this->render('index',['model'=>$model,'paginate'=>$paginate]);
    }
    public function actionAdd(){
        $model=new Brand();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model->file=UploadedFile::getInstance($model,'file');
            if($model->validate() && $model->saveAll()){
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model->file=UploadedFile::getInstance($model,'file');
            if($model->saveAll()){
                return $this->redirect(['brand/index']);
            }
            var_dump($model->errors);exit;
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel($id){
        $model=Brand::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save(false);
        return $this->redirect(['brand/index']);
    }
}