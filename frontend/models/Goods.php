<?php
namespace frontend\models;

use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord {
    public static function tableName(){
            return 'goods';
    }
    static function getGoods($ids,$keyWord){
        $goods=self::find();
        if($keyWord){
            $goods->andWhere(['like','name',$keyWord]);
        }
//        foreach ($ids as $id){
//            $goods->orWhere('goods_category_id='.$id);
//        }
        //使用in 而不是or
        $goods->andWhere(['in','goods_category_id',$ids]);
        $count=$goods->count();
        $pageSize=3;
        $currentPage=\Yii::$app->request->get('page',1);
        if($currentPage>$pageSize){
            $currentPage=$pageSize;
        }elseif ($currentPage<1){
            $currentPage=1;
        }
        $offset=$pageSize*($currentPage-1);
        $totalPage=ceil($count/$pageSize);
        return [$goods->offset($offset)->limit($pageSize)->asArray()->all(),$totalPage];
    }
    public function getImages(){
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id'])->asArray()->all();
    }
    public function getIntro(){
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id'])->one();
    }
}