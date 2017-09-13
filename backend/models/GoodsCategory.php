<?php

namespace backend\models;

use creocoder\nestedsets\NestedSetsBehavior;
use frontend\models\MenuQuery;
use Yii;

/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => '树id',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'name' => '分类名称',
            'parent_id' => '父分类id',
            'intro' => '简介',
        ];
    }
    public function behaviors()
    {
        return [
            'tree'=>[
                'class'=>NestedSetsBehavior::className(),
                'treeAttribute'=>'tree'
            ]
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(){
        return new GoodsCategoryQuery(get_called_class());
    }
    public static function getZtree(){
        $nodes=self::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        return $nodes;
    }
    public static function genZtree(){
        $nodes=self::find()->select(['id','parent_id','name','depth'])->orderBy('parent_id')->asArray()->all();
        return self::generate($nodes,0);
    }
    //php的参数指定有bug毛病,会提升执行的次序,所以不要在函数的参数使用$p=1
    protected static function generate(&$nodes,$pid){
        static $list=[];
        foreach ($nodes as $node){
            if($node['parent_id']==$pid){
                $node['name']=str_repeat('==',$node['depth']).$node['name'];
                array_push($list,$node);
                self::generate($nodes,$node['id']);
            }
        }
        return $list;
    }
}