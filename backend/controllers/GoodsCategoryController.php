<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\web\HttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $ztrees=GoodsCategory::genZtree();
        return $this->render('index',['ztrees'=>$ztrees]);
    }

    public function actionAdd()
    {
        $model = New GoodsCategory();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate()) {
                if ($model->parent_id) {
                    $parent = GoodsCategory::find()->where(['id' => $model->parent_id])->One();
                    $model->appendTo($parent);
                } else {
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['index']);
            }
        }
        $ztree = json_encode($model::getZtree());
        return $this->render('add', ['model' => $model, 'ztree' => $ztree]);
    }

    public function actionEdit($id = 0){
        if ($id == 0) {
            throw new HttpException(403, '非法请求');
        }
        $model = GoodsCategory::findOne(['id' => $id]);
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $childrens = [$id]+array_column($model->children()->asArray()->select(['id'])->all(), 'id');
            if ($model->load($request->post()) && $model->validate()) {
                if(!in_array($model->parent_id,$childrens)){
                    if ($model->parent_id) {
                        $parent = GoodsCategory::find()->where(['id' => $model->parent_id])->One();
                        //节点不能修改到自己或者子节点中,应该尝试移动,来捕获错误
                        try{
                            //尝试移动,移动出错会抛出异常
                            $model->appendTo($parent);
                        }
                        //捕获错误,如果出错会显示403错误,并显示提示
                        catch (\Exception $e){
                            throw new HttpException(403,'非法操作,尝试移动父分类到子分类中');
                        }
                    } else {
                        if($model->getOldAttribute('parent_id')===0){
                            $model->save();
                        }else{
                            $model->makeRoot();
                        }
                    }
                    \Yii::$app->session->setFlash('success', '添加成功');
                    return $this->redirect(['index']);
                }
                else{
                    $model->addError('parent_id','不能添加在自己或者子类中');
                }
            }
        }
        $ztree = json_encode($model::getZtree());
        return $this->render('add', ['model' => $model, 'ztree' => $ztree]);
    }
    public function actionDel($id=0){
        if ($id == 0) {
            throw new HttpException(403, '非法请求');
        }
        $model=GoodsCategory::findOne(['id'=>$id]);;
        if($model->isLeaf()){
            $model->deleteWithChildren();
            return $this->redirect(['index']);
        }
        throw new HttpException(403, '还有子类不能删除');
    }
}
