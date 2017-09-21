<?php
/**
 * @var $this \yii\web\View
 * @var $permissions \backend\models\Permission
 */

$this->registerCssFile('http://cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.css');
$this->registerJsFile('https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('http://cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.js',['depends'=>\yii\web\JqueryAsset::className()]);

$p=json_encode($permissions);
$edit=\yii\helpers\Url::to(['rbac/edit-permission']);
$del=\yii\helpers\Url::to(['rbac/del-permission']);
$js=<<<JS
$(document).ready( function () {
    t=$('#table_id_example').DataTable(
       {
            data:{$p},
            columns:[
               {data:'name'},
               {data:'description'},
               {data:null,"orderable": false}
            ],
            "language": {
                "paginate": {
                  "next": "&gt;",
                  'previous':"&lt;"
                }
            }
       }
    );
    //数据表每次在搜索和排序后触发匿名函数并重绘表单
    t.on('order.dt search.dt',function() {
        //获取第3列
        t.column(2, {
            //在search,order事件应用
            "search": 'applied',
            "order": 'applied'
        
        })
        //所有单元格node
        .nodes().each(function(cell, i) {
            var name=$(cell).closest('tr').find('td:first').text();
            cell.innerHTML = '<a href="{$edit}?name='+name+'" class="btn btn-info">修改</a><a href="{$del}?name='+name+'" class="btn btn-danger">删除</a>';
    });
}).draw();

});

JS;
$this->registerJs($js);
?>

<table id="table_id_example" class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>权限路由</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
