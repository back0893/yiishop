<?php
/* @var $this yii\web\View */
$this->title='我的app';
?>
<?=\yii\bootstrap\Html::a('添加',\yii\helpers\Url::to(['add']))?>
<table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($ztrees as $ztree):?>
    <tr>
        <td><?=$ztree['id']?></td>
        <td><?=$ztree['name']?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['edit','id'=>$ztree['id']]))?>
            <?=\yii\bootstrap\Html::a('删除',\yii\helpers\Url::to(['del','id'=>$ztree['id']]))?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>