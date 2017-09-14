<?php
/* @var $this yii\web\View
*  @var $rows backend\models\Goods
 */
echo $this->render('search.php',['search'=>$search]);
echo $sort->link('name').'|'.$sort->link('price');
?>
<table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>商品名称</th>
        <th>货号</th>
        <th>商标</th>
        <th>品牌</th>
        <th>分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>售卖情况</th>
        <th>商品信息</th>
        <th>创建时间</th>
        <th>浏览次数</th>
        <th>相册</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $row):?>
    <tr>
        <td><?=$row->name?></td>
        <td><?=$row->sn?></td>
        <td><img src="<?=$row->logo?>" style="width: 50px"></td>
        <td><?=$row->brand->name?></td>
        <td><?=$row->cate->name?></td>
        <td><?=$row->market_price?></td>
        <td><?=$row->shop_price?></td>
        <td><?=$row->stock?></td>
        <td><?=$row->is_on_sale?'上架':'下架'?></td>
        <td><?=$row->status?'正常':'回收站'?></td>
        <td><?=date('Y-m-d',$row->create_time)?></td>
        <td><?=$row->view?></td>
        <td> <?=\yii\helpers\Html::a('相册',\yii\helpers\Url::to(['gallery/gallery','id'=>$row->id]))?></td>
        <td>
            <?=\yii\helpers\Html::a('修改',\yii\helpers\Url::to(['edit','id'=>$row->id]))?>
            <?=\yii\helpers\Html::a('删除',\yii\helpers\Url::to(['del','id'=>$row->id]))?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?=\yii\widgets\LinkPager::widget(['pagination'=>$paginate]);?>