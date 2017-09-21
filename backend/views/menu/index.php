<?php
/* @var $this yii\web\View */
/* @var $datas array */
use yii\bootstrap\Html;
use yii\helpers\Url;
?>
    <div id="showinfo">

    </div>
    <table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>名称</th>
        <th>路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($datas as $data):?>
    <tr>
        <td><?=str_repeat('--',$data['pId']?3:0).$data['name']?></td>
        <td><?=$data['route']?></td>
        <td><?=$data['sort']?></td>
        <td>
            <?=Html::a('修改',Url::to(['menu/edit','id'=>$data['id']]))?>
            <?=Html::a('删除',Url::to('javascript:void(0)'),['data-id'=>$data['id'],'class'=>'del'])?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
    $del=Url::to(['del']);
    $js=<<<JS
    var showinfo=$('#showinfo');
    $('tbody').on('click','.del',function() {
        self=$(this);
        $.post('{$del}',{'id':self.data('id')},function(data) {
            data=JSON.parse(data);
            var html='<div class="alert alert-danger">'
                +'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;    </button>';
            if(data.error!=0){
               html+= '<strong>错误111:</strong>'+data.info
            }else{
                self.closest('tr').remove();
                html+= '<strong>成功222:</strong>'+data.info
            }
            html+='</div>';
            showinfo.html(html);
        });
        return false;
    });
JS;
$this->registerJs($js);
