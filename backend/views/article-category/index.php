<?php
$status=[1=>'显示',-1=>'删除',0=>'隐藏']
?>
<?=\yii\bootstrap\Html::a('添加',\yii\helpers\Url::to(['article-category/add']));?>
<table class="table table-bordered table-hover">
    <thead>
    <tr>
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
        <td><?=$m->name?></td>
        <td><?=$m->sort?></td>
        <td><?=$status[$m->status]?></td>
        <td><?=mb_substr($m->intro,0,20).'...'?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['article-category/edit','id'=>$m->id]));?>
            <?=\yii\bootstrap\Html::a('删除','javascript:void(0)',['class'=>'del','data-id'=>$m->id]);?>
        </td>
    </tr>
    </tbody>
    <?php endforeach;?>
</table>
<?=\yii\widgets\LinkPager::widget(['pagination'=>$paginate])?>
<span style="hidden" id='required' data-del="<?=\yii\helpers\Url::to(['brand/del'])?>" data-csrf="<?=\Yii::$app->request->csrfToken?>"></span>
