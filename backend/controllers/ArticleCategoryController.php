<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use yii\data\Pagination;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=ArticleCategory::find();
        $paginate=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>5
        ]);
        $model=$query->orderBy('sort DESC')->limit($paginate->limit)->offset($paginate->offset)->all();
        return $this->render('index',['model'=>$model,'paginate'=>$paginate]);
    }
    public function actionAdd(){
        $model= new ArticleCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            $this->redirect(['article-category/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            $this->redirect(['article-category/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel(){
        $id=\Yii::$app->request->post('id','0');
        if($id){
            $article=Article::findOne(['id'=>$id]);
            $article->status=-1;
            return json_encode($article->save());
        }
        return 'false';
    }
}
