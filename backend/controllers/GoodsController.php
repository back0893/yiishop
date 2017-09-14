<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\Search;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\data\Sort;
use yii\web\HttpException;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $search=new Search();
        $sort=new Sort(
          [
              'attributes'=>[
                  'name'=>['label'=>'名称'],
                  'price'=>[
                      'asc'=>['shop_price'=>SORT_ASC,'market_price'=>SORT_ASC],
                      'desc'=>['shop_price'=>SORT_DESC,'market_price'=>SORT_DESC],
                      'default'=>SORT_DESC,
                      'label'=>'价格'
                  ]
              ],
          ]
        );
        $search->load(\Yii::$app->request->get());
        $query=$search->getSearch();
        $paginate=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>3
        ]);
        $rows=$query->limit($paginate->limit)->offset($paginate->offset)->orderBy($sort->orders)->all();
        return $this->render('index',['rows'=>$rows,'search'=>$search,'paginate'=>$paginate,'sort'=>$sort]);
    }
    public function actionAdd(){
        $goods=new Goods();
        $goodsIntro=new GoodsIntro();
        $request=\Yii::$app->request;
        if($request->isPost){
            $goods->load($request->post());
            if(!GoodsCategory::findOne(['id'=>$goods->goods_category_id])->isLeaf()){
                throw new HttpException('403','禁止添加');
            }
            $goodsIntro->load($request->post());
            if($goods->validate() && $goodsIntro->validate()){
                $count=GoodsDayCount::getCount();
                //获得一个事物实例
                $trans=\Yii::$app->db->beginTransaction();
                $goods->sn=date('Ymd').sprintf('%04d',$count);
                $valiate=$goods->save();
                $goodsIntro->goods_id=$goods->id;
                $valiate=$goodsIntro->save()&&$valiate;
//                判断提交成功,成功才会提交
                if($valiate){
                    $trans->commit();
                }
                else{
                    $trans->rollBack();
                    var_dump($goods->errors,$goodsIntro->errors);exit;

                }
                \Yii::$app->session->setFlash('success', '添加成功');
                $this->redirect(['goods/index']);
            }
        }
        $ztree=json_encode(GoodsCategory::getZtree());
        $dropList=Brand::getDropList();
        return $this->render('add',['goods'=>$goods,'goodsIntro'=>$goodsIntro,'ztree'=>$ztree,'dropList'=>$dropList]);
    }
    public function actionEdit($id){
        $goods=Goods::findOne(['id'=>$id]);
        $goodsIntro=GoodsIntro::findOne(['goods_id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            $goods->load($request->post());
            $goodsIntro->load($request->post());
            if($goods->validate() && $goodsIntro->validate()){
                $count=GoodsDayCount::getCount();
                //获得一个事物实例
                $trans=\Yii::$app->db->beginTransaction();
                $valiate=$goods->save();
                $goodsIntro->goods_id=$goods->id;
                $valiate=$goodsIntro->save()&&$valiate;
//                判断提交成功,成功才会提交
                if($valiate){
                    $trans->commit();
                }
                else{
                    $trans->rollBack();
                    var_dump($goods->errors,$goodsIntro->errors);exit;

                }
                \Yii::$app->session->setFlash('success', '修改成功');
                $this->redirect(['goods/index']);
            }
        }
        $ztree=json_encode(GoodsCategory::getZtree());
        $dropList=Brand::getDropList();
        return $this->render('add',['goods'=>$goods,'goodsIntro'=>$goodsIntro,'ztree'=>$ztree,'dropList'=>$dropList]);
    }
    public function actionDel($id){
        $goods=Goods::findOne(['id'=>$id]);
        if($goods->status){
            $goods->status=0;
            $goods->save();
        }
        else{
            $goods->delete();
            GoodsGallery::deleteAll(['goods_id'=>$id]);
        }
        return $this->redirect(['goods/index']);
    }

    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/goods/logo',
                'baseUrl' => '@web/goods/logo',
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
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $key = $action->getFilename();
                    $qiniu->uploadFile($action->getSavePath(),$key);
                    $url = $qiniu->getLink($key);
                    $action->output['fileUrl'] = $url;
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
    public function actionTest(){
        //数据的提供者,用来快速提供排序,分页,和查询结果
        //query是一个活动记录实例
        //pagination是分页的配置
        //sort是排序的配置
        $provider=new ActiveDataProvider([
           'query'=>Goods::find(),
           'pagination'=>[
               'pageSize'=>2
           ],
            'sort'=>[
                'attributes'=>['name',
                    'price'=>[
                        'asc'=>['shop_price'=>SORT_ASC,'market_price'=>SORT_ASC],
                        'desc'=>['shop_price'=>SORT_DESC,'market_price'=>SORT_DESC],
                        'default'=>SORT_DESC,
                        'label'=>'价格'
                    ]
                ]
            ]
        ]);
        $sort=$provider->getSort();
        $paginate=$provider->getPagination();
        $rows=$provider->getModels();
        $search=new Search();
        return $this->render('index1',['rows'=>$rows,'search'=>$search,'paginate'=>$paginate,'sort'=>$sort]);
    }
    public function actionShow($id){
        $name=Goods::find()->select('name')->where(['id'=>$id])->One();
        if(!$name){
            throw new HttpException('404','页面走丢了');
        }
        $intro=GoodsIntro::find()->select('intro')->where(['goods_id'=>$id])->One();
        $imgs=GoodsGallery::find()->select('path')->asArray()->where(['goods_id'=>$id])->all();
        return $this->render('show',['name'=>$name,'intro'=>$intro,'imgs'=>$imgs]);
    }
}
