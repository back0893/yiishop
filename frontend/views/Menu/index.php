<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
<?php
$list=json_encode($list);
$js=<<<JS
 var zTreeObj;
   // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {};
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = {$list};
   $(document).ready(function(){
      zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);})

JS;
$this->registerJs($js);