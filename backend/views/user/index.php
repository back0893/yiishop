<?php
/**
 * @var $this yii\web\View
 * @var $users \backend\models\User
 * @var $paginate \yii\data\Pagination
 */
use yii\bootstrap\Html;
use yii\helpers\Url;
$status=['-1'=>'ban','0'=>'员工',1=>'管理员',2=>'超级管理员'];
echo Html::a('添加',Url::to(['add']));
?>
<table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>名称</th>
        <th>邮箱</th>
        <th>角色</th>
        <th>创建时间</th>
        <th>最后修改时间</th>
        <th>最后登录时间</th>
        <th>最后登录ip</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user):?>
    <tr>
        <td><?=$user->username?></td>
        <td><?=$user->email?></td>
        <td><?=$status[$user->status]?></td>
        <td><?=date('Y-m-d',$user->created_at)?></td>
        <td><?=date('Y-m-d',$user->updated_at)?></td>
        <td><?=date('Y-m-d',$user->last_login_time)?></td>
        <td><?=$user->last_login_ip?></td>
        <td>
            <?=Html::a('修改',Url::to(['edit','id'=>$user->id]))?>
            <?=Html::a('删除',Url::to(['del','id'=>$user->id]))?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
