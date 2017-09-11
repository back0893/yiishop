<?php
?>
<table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>名称</th>
        <th>文章分类</th>
        <th>简介</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($model as $m):?>
    <tr>
        <td><?=$m->name?></td>
        <td><?=$m->category->name?></td>
        <td><?=$m->intro?></td>
        <td><?=$m->status?'隐藏':'正常'?></td>
        <td><?=date('Y-m-d',$m->create_time)?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['article/edit','id'=>$m->id]))?>
            <?=\yii\bootstrap\Html::a('删除','javascript:void(0)',['class'=>'del','data-id'=>$m->id])?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<span style="hidden" id='required' data-del="<?=\yii\helpers\Url::to(['brand/del'])?>" data-csrf="<?=\Yii::$app->request->csrfToken?>"></span>