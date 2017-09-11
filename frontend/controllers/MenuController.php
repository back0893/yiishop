<?php
namespace frontend\controllers;
use frontend\models\Menu;
use yii\base\Controller;
use yii\helpers\Json;


class MenuController extends Controller{
    public function actionRoot(){
        //创建一个根node
        $countries =new Menu(['name'=>'Countries']);
        $countries->tree='国家';
        $countries->parent_id=0;
        $countries->makeRoot();
        $pid=$countries->id;
        //为根节点添加一个子节点
        $australia = new Menu(['name' => 'Australia']);
        $australia->tree='澳大利亚';
        $australia->parent_id=$pid;
        $australia->prependTo($countries);
        $newZeeland = new Menu(['name' => 'New Zeeland']);
        $newZeeland->parent_id=$pid;
        $newZeeland->insertAfter($australia);
        $unitedStates = new Menu(['name' => 'United States']);
        $unitedStates->tree='阿妹你看';
        $unitedStates->parent_id=$pid;
        $unitedStates->insertBefore($australia);
    }
    public function actionAdd(){
        $countries =Menu::findOne(['name'=>'Countries']);
        $china = new Menu(['name' => 'TEST country']);
        $pid=$countries->id;
//        $china->parent_id=$pid;
        $china->appendTo($countries);
//        $china=Menu::findOne(['name'=>'china']);
//        var_dump($china->id);
//        $gz=new Menu(['name'=>'gz']);
//        $gz->parent_id=$china->id;
//        $gz->tree='广州';
//        $gz->appendTo($china);
        var_dump($china->errors);
    }
    //嵌套规则,是每一个节点就是一个集合,一定有一个name,可能有children
    //每一个children也是一个node
    public function gene1($parents){
        $tmp=[];
        foreach ($parents as $parent){
            $l=[];
            $l['name']=$parent->name;
            $l['id']=$parent->id;
            $p=$this->gene($parent->children(1)->all());
            if(count($p)){
                $l['children']=$p;
            }
            $tmp[]=$l;
        }
        return $tmp;
    }
    public function gene2($parents){
        $tmp=[];
        foreach ($parents as $parent){
            $tmp[]=['id'=>$parent->id,'name'=>$parent->name,'pid'=>$parent->parent_id,'url'=>'index',"jojo"=>"testName"];
        }
        return $tmp;
    }
    public function actionIndex(){
        $roots=Menu::find()->roots()->all();
        $list=$this->gene2($roots);
//        echo Json::encode($list);exit;
//        echo json_encode($list);exit;
        return $this->render('index',['list'=>$list]);
    }
}