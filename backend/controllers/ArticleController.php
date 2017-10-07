<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use app\models\ArticleDetail;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Article::find();
        $paginate=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>5
        ]);
        $model=$query->where(['!=','status','-1'])->orderBy('sort DESC')->limit($paginate->limit)->offset($paginate->offset)->all();
        return $this->render('index',['model'=>$model,'paginate'=>$paginate]);
    }

    public function actionAdd(){
        //多模型的的同时输入,需要实例化需要的模型
        $article=new Article();
        $articleDetail=new ArticleDetail();
        $request=\Yii::$app->request;
        //load的传入的值为空时,会返回false,同时load只会载入安全的值,
        if($article->load($request->post()) && $articleDetail->load($request->post())){
            //模型都需要进行验证,只有都验证通过了才能保存
            $validate=$article->validate();
            $validate=$articleDetail->validate()&&$validate;
            if($validate){
                $article->save(false);
                //这里文章的详细表表不能插入,必须在获得文章的id,后才能保存,以1对1方式保存
                $articleDetail->article_id=\Yii::$app->db->lastInsertID;
                $articleDetail->save(false);
                return $this->redirect(['article/index']);
            }
            var_dump($article->errors,$articleDetail->errors);exit;
        }
        //获取文章分类,不好放删除状态
        $cats=ArrayHelper::map(ArticleCategory::find()->where(['!=','status',-1])->all(),'id','name');
        return $this->render('add',['article'=>$article,'articleDetail'=>$articleDetail,'cats'=>$cats]);
    }

    public function actionEdit($id){
        $article=Article::findOne(['id'=>$id]);
        $articleDetail=ArticleDetail::findOne(['article_id'=>$id]);
        $request=\Yii::$app->request;
        if($article->load($request->post()) && $articleDetail->load($request->post())){
            $validate=$article->validate();
            $validate=$articleDetail->validate()&&$validate;
            if($validate){
                $article->save(false);
                $articleDetail->save(false);
                return $this->redirect(['article/index']);
            }
            var_dump($article->errors,$articleDetail->errors);exit;
        }
        $cats=ArrayHelper::map(ArticleCategory::find()->where(['!=','status',-1])->all(),'id','name');
        return $this->render('add',['article'=>$article,'articleDetail'=>$articleDetail,'cats'=>$cats]);
    }
    public function actionDel(){
        $id=\Yii::$app->request->post('id','0');
        if($id){
            $article=Article::findOne(['id'=>$id]);
            $article->status=-1;
            return json_encode($article->save());
        }
        return 'false';
//        return $this->redirect(['article/index']);
    }
}
