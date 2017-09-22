<?php
/**
 * @var $this \yii\web\View;
 * @var $goodss \frontend\models\Goods
 */
use yii\helpers\Url;
$this->title='购物车页面';
$this->registerCssFile('/style/cart.css');
$this->registerJsFile('/js/cart1.js',['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/myjs/carts.js',['depends'=>'yii\web\JqueryAsset'])
?>
	<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
			<div class="flow fr">
				<ul>
					<li class="cur">1.我的购物车</li>
					<li>2.填写核对订单信息</li>
					<li>3.成功提交订单</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<div style="clear:both;"></div>

	<!-- 主体部分 start -->
	<div class="mycart w990 mt10 bc">
		<h2><span>我的购物车</span></h2>
		<table>
			<thead>
				<tr>
					<th class="col1">商品名称</th>
					<th class="col3">单价</th>
					<th class="col4">数量</th>	
					<th class="col5">小计</th>
					<th class="col6">操作</th>
				</tr>
			</thead>
			<tbody>
            <?php foreach ($goodss as $goods):?>
                <tr data-id="<?=$goods->id?>">
                    <td class="col1"><a href="<?=Url::to(['index/goods','id'=>$goods->id])?>"><img src="<?=$goods->logo?>" alt="" /></a>  <strong><a href=""><?=$goods->name?></a></strong></td>
                    <td class="col3">￥<span><?=$goods->shop_price?></span></td>
                    <td class="col4">
                        <a href="javascript:;" class="reduce_num"></a>
                        <input type="text" name="amount" value="<?=$cookies[$goods->id]?>" class="amount"/>
                        <a href="javascript:;" class="add_num"></a>
                    </td>
                    <td class="col5">￥<span>
                        <?php
                            printf('%.2f',$goods->shop_price*$cookies[$goods->id]);
                        ?></span></td>
                    <td class="col6"><a href="javascript:void(0)" class="del">删除</a></td>
                </tr>
            <?php endforeach;?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6">购物金额总计： <strong>￥ <span id="total">0</span></strong></td>
				</tr>
			</tfoot>
		</table>
		<div class="cart_btn w990 bc mt10">
			<a href="<?=Url::to(['index/index'])?>" class="continue">继续购物</a>
			<a href="<?=Url::to(['cart/entry-order'])?>" class="checkout">结 算</a>
		</div>
	</div>
	<!-- 主体部分 end -->
<script>
    var token='<?=\Yii::$app->request->csrfToken?>';
    var editGoods='<?=Url::to(['cart/edit'])?>';
    var delGoods='<?=Url::to(['cart/del'])?>';
</script>