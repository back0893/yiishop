<?php
/* @var $this yii\web\View
 * @var $goods backend\models\Goods
 * @var $goodsIntro backend\models\GoodsIntro
 *
 */
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($goods,'name');
echo $form->field($goods,'logo')->hiddenInput();
echo "<div><img id='showLog' src='{$goods->logo}' style='width: 50px;'></div>";
echo \yii\bootstrap\Html::fileInput('upload', NULL, ['id' => 'upload']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'upload',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['goods_id' => $goods->id],
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
        $('#goods-logo').val(data.fileUrl);
        $('#showLog').attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);
echo '<div><ul id="treeDemo" class="ztree"></ul></div>';
echo $form->field($goods,'goods_category_id')->hiddenInput();
echo $form->field($goods,'brand_id')->dropDownList($dropList);
echo $form->field($goods,'market_price');
echo $form->field($goods,'shop_price');
echo $form->field($goods,'stock');
echo $form->field($goods,'sort');
echo $form->field($goods,'is_on_sale')->radioList([1=>'出售',0=>'下架']);
echo $form->field($goods,'status')->radioList([1=>'正常',0=>'回收站']);
echo $form->field($goods,'view')->textInput(['default'=>0]);
echo $form->field($goodsIntro,'intro')->widget('kucha\ueditor\UEditor',[]);
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();
$js=<<<JS
    var zTreeObj;
   // zTree 的参数配置，深入使用请参考 api 文档（setting 配置详解）
   var setting = {
      data:{
           simpleData:{
           enable:true,
           idKey:'id',
           pIdKey: "parent_id",
           rootPid:0
       }
      },
      callback:{
          onClick:function(event, treeId, treeNode) {
            // console.log(treeNode.id);
            $('#goods-goods_category_id').val(treeNode.id)
          }
      }
   };
   // zTree 的数据属性，深入使用请参考 api 文档（ztreenode 节点数据详解）
    var zNodes = {$ztree} ;
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    var node= zTreeObj.getNodeByParam('id',"{$goods->goods_category_id}",null);
    zTreeObj.selectNode(node);
JS;
$this->registerJs($js);
$this->registerCssFile('@web/tree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/tree/js/jquery.ztree.core.min.js',['depends'=>\yii\web\JqueryAsset::className()]);