<?php
/**
 * @var $model backend\models\GoodsCategory
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->hiddenInput();
echo '<div><ul id="treeDemo" class="ztree"></ul></div>';
echo $form->field($model,'intro')->textarea();
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
            $('#goodscategory-parent_id').val(treeNode.id)
          }
      }
   };
   // zTree 的数据属性，深入使用请参考 api 文档（ztreenode 节点数据详解）
    var zNodes = {$ztree} ;
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    var node= zTreeObj.getNodeByParam('id',"{$model->parent_id}",null);
    zTreeObj.selectNode(node);
JS;
$this->registerJs($js);
$this->registerCssFile('@web/tree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/tree/js/jquery.ztree.core.min.js',['depends'=>\yii\web\JqueryAsset::className()]);