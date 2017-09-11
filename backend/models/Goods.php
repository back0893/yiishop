<?php

namespace backend\models;

use flyok666\qiniu\Qiniu;
use Yii;
use yii\behaviors\TimestampBehavior;
use flyok666\uploadifive\UploadAction;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock_int
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 * @property integer $view_time
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale','status','sort', 'create_time', 'view'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'logo图片',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'sort' => '排序',
            'status'=>'状态',
            'create_time' => '添加时间',
            'view' => '浏览次数',
        ];
    }
    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'createdAtAttribute'=>'create_time',
                'updatedAtAttribute'=>false
            ],
        ];
    }
    public function actions() {
        return [
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
                    $qiniu = new Qiniu(Yii::$app->params['qiniu']);
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
    public function getIntro(){
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    public function getCate(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    public function getGallery(){
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
    }
}
