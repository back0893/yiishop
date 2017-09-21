<?php

namespace backend\controllers;

use backend\models\Menu;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $datas=Menu::getArrays();
        return $this->render('index',['datas'=>$datas]);
    }
    public function actionAdd()
    {
        $model = new Menu();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->dealData();
            $model->save();
        }
        $auth=Yii::$app->authManager;
        //显示权限路径
        $routes=ArrayHelper::map($auth->getPermissions(),'name','name');
        $routes=['0'=>'===选择路由===']+$routes;
        //显示id,name的顶级菜单
        $topMenus=$model->find()->select(['id','name'])->where(['pId'=>0])->asArray()->all();
        $topMenus=['0'=>'===顶级目录===']+ArrayHelper::map($topMenus,'id','name');
        return $this->render('add', [
            'model' => $model,
            'topMenus'=>$topMenus,
            'routes'=>$routes
        ]);
    }
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->dealData();
            $model->save();
            return $this->redirect(['index']);
        }
        $auth=Yii::$app->authManager;
        //显示权限路径
        $routes=ArrayHelper::map($auth->getPermissions(),'name','name');
        $routes=['0'=>'===选择路由===']+$routes;
        //显示id,name的顶级菜单
        $topMenus=$model->find()->select(['id','name'])->where(['pId'=>0])->asArray()->all();
        $topMenus=['0'=>'===顶级目录===']+ArrayHelper::map($topMenus,'id','name');
        return $this->render('add', [
            'model' => $model,
            'topMenus'=>$topMenus,
            'routes'=>$routes
        ]);
    }
    public function actionTest(){
        var_dump(Menu::getData());
    }
    public function actionDel(){
        $id=\Yii::$app->request->post('id',0);
        $model=Menu::findOne(['id'=>$id]);
        if(!$model){
            return json_encode(['error'=>1,'info'=>'没有找到菜单']);
        }
        $children=Menu::find()->where(['pId'=>$id])->count('id');
        if($children){
            return json_encode(['error'=>1,'info'=>'还有子菜单,你不能这样删除']);
        }
        $model->delete();
        return json_encode(['error'=>0,'info'=>'成功删除']);

    }
}
