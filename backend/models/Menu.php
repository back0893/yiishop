<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;
/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $route
 * @property string $sort
 * @property integer $pId
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','pId'],'required'],
            [['sort', 'pId'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['route'], 'string', 'max' => 100],
            ['name','unique','filter'=>['!=','name',$this->name]]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '菜单名称',
            'route' => '地址/路由',
            'sort' => '排序',
            'pId'=>'一级目录'
        ];
    }
    public function dealData(){
        if($this->pId==0){
            $this->route='';
        }
    }

    public static function getArrays(){
        $rows=self::find()->select(['id','name','pId','route','sort'])->asArray()->orderBy(['sort'=>SORT_DESC])->where(['pId'=>0])->all();
        return self::makeNodes($rows);
    }

    protected static function makeNodes(&$rows,$pid=0){
        $list=[];
        foreach ($rows as &$row){
            $childrens=self::find()->orderBy(['sort'=>SORT_DESC])->select(['id','name','pId','route','sort'])->where(['pId'=>$row['id']])->asArray()->all();
            $list[]=$row;
            $list=array_merge($list,$childrens);
        }
        return $list;
    }
    public static function getData(){
        $rows=self::find()->select(['id','name','route','pId'])->orderBy(['sort'=>SORT_DESC])->all();
        $menuItems=self::makeItems($rows);
        $menus=[];
        foreach ($menuItems as $menuItem){
            if($menuItem['items']){
                $menus[]=$menuItem;
            }
        }
        return $menus;
    }

    protected static function makeItems(&$rows,$pid=0){
        $user=\Yii::$app->user;
        $menuItems=[];
        foreach ($rows as $row){
            if($row->pId==$pid){
                if($pid==0 || $user->can($row->route)){
                    $menuItems[]=[
                        'label'=>$row->name,
                        'url'=>[$row->route?$row->route:'#'],
                        'items'=>self::makeItems($rows,$row->id),
                    ];

                }
            }
        }
        return $menuItems;
    }
}
