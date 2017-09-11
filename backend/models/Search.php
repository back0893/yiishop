<?php
namespace backend\models;
use yii\base\Model;

class Search extends Model{
    public $name;
    public $sn;
    public $min;
    public $max;
    public function rules()
    {
        return [
            [['sn','name','max','min'],'string']
        ];
    }

    public function getSearch(){
        $query=Goods::find();
        $query->andFilterWhere(['like','sn',$this->sn]);
        $query->andFilterWhere(['like','name',$this->name]);
        $query->andFilterWhere(['>=','shop_price',$this->min]);
        $query->andFilterWhere(['<=','shop_price',$this->max]);
        return $query;
    }
}