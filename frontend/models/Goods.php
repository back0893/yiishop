<?php
namespace frontend\models;

use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord {
    public static function tableName(){
            return 'goods';
    }
    static function getGoods($ids){
        $goods=self::find();
//        foreach ($ids as $id){
//            $goods->orWhere('goods_category_id='.$id);
//        }
        //使用in 而不是or
        $goods->where(['in','goods_category_id',$ids]);
        return $goods->asArray()->all();
    }
    public function getImages(){
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id'])->asArray()->all();
    }
    public function getIntro(){
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id'])->one();
    }
}