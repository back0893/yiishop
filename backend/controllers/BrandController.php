<?php

namespace backend\controllers;

use backend\models\Brand;
use flyok666\qiniu\Qiniu;
use yii\data\Pagination;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Brand::find();
        $paginate=new Pagination([
            'totalCount'=>$query->where(['>','status',-1])->count(),
            'defaultPageSize'=>2
        ]);
        $model=Brand::find()->where(['>','status','-1'])->orderBy('sort DESC')->limit($paginate->limit)->offset($paginate->offset)->all();
        return $this->render('index',['model'=>$model,'paginate'=>$paginate]);
    }
    public function actionAdd(){
        $model=new Brand();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
//            $model->file=UploadedFile::getInstance($model,'file');
            if($model->validate() && $model->save(false)){
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            $oldLogo=$model->logo;
            $model->load($request->post());
//            $model->file=UploadedFile::getInstance($model,'file');
            if($model->validate()&&$model->save(false)){
                @unlink(\Yii::getAlias('@webroot').$oldLogo);
                return $this->redirect(['brand/index']);
            }
            var_dump($model->errors);exit;
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel(){
        $id=\Yii::$app->request->post('id','0');
        if($id){
            $article=Brand::findOne(['id'=>$id]);
            $article->status=-1;
            return json_encode($article->save());
        }
        return 'false';
    }
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
//                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "logo/{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 2 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $qiniu=new Qiniu(\Yii::$app->params['qiniu']);
                    $key=$action->getFilename();
                    $file=$action->getSavePath();
                    $qiniu->uploadFile($file,$key);
                    $action->output['fileUrl'] = $qiniu->getLink($key);
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
    public function actionTest(){
        $qiniu=new Qiniu(\Yii::$app->params['qiniu']);
        $root=\Yii::getAlias('@webroot');
        $key='1.jpg';
        $file=$root.'/'.$key;
        var_dump($qiniu->uploadFile($file,'1.jpg'));
        var_dump($qiniu->getLink('1.jpg'));
    }
}
