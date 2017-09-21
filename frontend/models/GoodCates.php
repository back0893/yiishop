<?php
namespace frontend\models;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class GoodCates extends ActiveRecord{
    public static function tableName()
    {
        return 'goods_category';
    }
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ]
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    public static function generateNodes($pid=0){
        $nodes=self::findAll(['parent_id'=>$pid]);
        $list=[];
        foreach ($nodes as $node){
            $temp=['name'=>$node->name];
            $temp['id']=$node->id;
            $temp['children']=self::generateNodes($node->id);
            $list[]=$temp;
        }
        return $list;
    }

    public static function find()
    {
        return new CatesQuery(get_called_class());
    }
    public static function getChildrenId($id){
        $self=self::findOne(['id'=>$id]);
        //children返回的activeQuery,所以select,where等需要在children之后才能使用
        $childrenId=$self->children()->select('id')->andWhere(['depth'=>2])->asArray()->all();
        return array_merge([$self->id],ArrayHelper::getColumn($childrenId,'id'));
    }
}