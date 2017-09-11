<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
<?php
    $js=<<<JS
 var zTreeObj;
   // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {};
   console.log(1);
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = [
   {name:"test1", open:true, children:[
      {name:"test1_1"}, {name:"test1_2",children:[{'name':'test1_1_1'}]}]},
   {name:"test2", open:true, children:[
      {name:"test2_1"}, {name:"test2_2"}]}
   ];
   $(document).ready(function(){
      zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);})

JS;
$this->registerJs($js);