<?php
/**
 * @var $this \yii\web\View
 * @var $name \backend\models\Goods
 * @var $intro \backend\models\GoodsIntro
 * @var $imgs \backend\models\GoodsGallery
 */
use yii\bootstrap\Html;
//转为图片墙
$showImages=[];
foreach ($imgs as $img){
    $temp=[];
    $temp['content']="<img src='{$img['path']}'/>";
    $showImages[]=$temp;
}
//var_dump($showImages);exit;
echo "<h1>{$name->name}</h1>";
echo \yii\bootstrap\Carousel::widget(
    [
        'items'=>$showImages,

    ]
);
echo "<div>{$intro->intro}</div>"
?>
