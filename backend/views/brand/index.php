<?php
$status=[1=>'显示',-1=>'删除',0=>'隐藏']
?>
<?=\yii\bootstrap\Html::a('添加',\yii\helpers\Url::to(['brand/add']));?>
<div id="body">
<table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>logo</th>
        <th>名称</th>
        <th>排序</th>
        <th>状态</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model as $m):?>
    <tr>
        <td><img <?=$m->logo?"src='/$m->logo?>'":''?> style="width: 50px"></td>
        <td><?=$m->name?></td>
        <td><?=$m->sort?></td>
        <td><?=$status[$m->status]?></td>
        <td><?=mb_substr($m->intro,0,20).'...'?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['brand/edit','id'=>$m->id]));?>
            <?=\yii\bootstrap\Html::a('删除','javascript:void(0)',['class'=>'del','data-id'=>$m->id]);?>
        </td>
    </tr>
    </tbody>
    <?php endforeach;?>
</table>
<?=\yii\widgets\LinkPager::widget(['pagination'=>$paginate])?>
</div>
