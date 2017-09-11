<?php
use \yii\helpers\Html;
use \yii\helpers\Url;
use yii\web\JsExpression;
/**
 * @var $gallerys backend\models\GoodsGallery
 * @var $this \yii\web\View
 */
$del=Url::to(['del']);
$form=\yii\bootstrap\ActiveForm::begin();
echo \yii\bootstrap\Html::fileInput('upload', NULL, ['id' => 'upload']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'upload',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['goods_id'=>$goods_id],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        var tbody=$('tbody');
        var html='';
        html='<tr><td><img src="'+data.fileUrl+'" style="width: 200px"></td>';
        html+='<td><a href="javascript:void(0)" class="del">删除</a></td></tr>';
        tbody.append(html);
    }
}
EOF
        ),
    ]
]);
$js=<<<JS
    $('tbody').on('click','.del',function(event) {
          var tr=$(this).closest('tr');
          var id=$(this).data('id');
          $.post('{$del}',{'id':id},function(data){
              data=JSON.parse(data);
              if(!data.error){
                  $(tr).remove();
              }
          })
    })
    
JS;
$this->registerJs($js);
?>
<div id="show">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>图片</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($gallerys as $gallery):?>
            <tr>
                <td>
                    <img src='<?=$gallery->path?>' style='width:200px'>
                </td>
                <td>
                    <a href="javascript:void(0)" class="del" data-id="<?=$gallery->id?>">删除</a>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>