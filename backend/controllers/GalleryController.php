<?php
namespace backend\controllers;
use flyok666\uploadifive\UploadAction;
use yii\web\Controller;
use backend\models\GoodsGallery;
use backend\models\Goods;
use flyok666\qiniu\Qiniu;

class GalleryController extends Controller{
    public function actionGallery($id){
        $request=\Yii::$app->request;
        $gallerys=Goods::findOne(['id'=>$id])->gallery;
        return $this->render('gallery',['goods_id'=>$id,'gallerys'=>$gallerys]);
    }
    public function actionDel(){
        $id=\Yii::$app->request->post('id',0);
        $id=trim($id);
        if($id){
            if(GoodsGallery::deleteAll(['id'=>$id])){
                echo json_encode(['error'=>0,'msg'=>'delete ok']);
            };
        }else{
            echo json_encode(['error'=>1]);
        }
    }
    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/goods/gallery',
                'baseUrl' => '@web/goods/gallery',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 2 * 1024 * 1024, //file size
                ],
                'afterSave' => function (UploadAction $action) {
                    //显示回显,直接在后台保存值数据库
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $key = $action->getFilename();
                    $qiniu->uploadFile($action->getSavePath(),$key);
                    $url = $qiniu->getLink($key);
                    $Gallery=new GoodsGallery();
                    $Gallery->goods_id=$_REQUEST['goods_id'];
                    $Gallery->path=$url;
                    $Gallery->save(false);
                    $action->output['fileUrl'] = $url;
                    $action->output['id'] = $Gallery->id;
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
}