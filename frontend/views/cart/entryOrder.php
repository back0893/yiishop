<?php
/**
 * @var $this \yii\web\View
 */
use yii\helpers\Url;
$this->registerCssFile('/style/fillin.css');
$this->registerJsFile('/js/cart2.js',['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/myjs/entryOrder.js',['depends'=>'yii\web\JqueryAsset']);
$price=0;
?>

	<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
			<div class="flow fr flow2">
				<ul>
					<li>1.我的购物车</li>
					<li class="cur">2.填写核对订单信息</li>
					<li>3.成功提交订单</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<div style="clear:both;"></div>

	<!-- 主体部分 start -->
	<div class="fillin w990 bc mt15">
		<div class="fillin_hd">
			<h2>填写并核对订单信息</h2>
		</div>
        <form action="" method="post" id="order_form">
            <input type="hidden" name="_csrf-frontend" value="<?=Yii::$app->request->csrfToken?>">
		<div class="fillin_bd">
			<!-- 收货人信息  start-->
			<div class="address">
				<h3>收货人信息</h3>
				<div class="address_info">
                <?php foreach ($addresses as $index=>$address):?>
                <p>
                    <input type="radio" value="<?=$address->id?>" name='address'">
                    <?php
                    $addr="$address->name $address->tel ".$address->getAddress('province')->name.' '. $address->getAddress('city')->name.' '.$address->getAddress('town')->name.' '."$address->address ";
                    echo $addr;
                    ?>
                </p>
                <?php endforeach;?>
				</div>

			</div>
			<!-- 收货人信息  end-->

			<!-- 配送方式 start -->
			<div class="delivery">
				<h3>送货方式 </h3>


				<div class="delivery_select">
					<table>
						<thead>
							<tr>
								<th class="col1">送货方式</th>
								<th class="col2">运费</th>
								<th class="col3">运费标准</th>
							</tr>
						</thead>
						<tbody>
                            <?php foreach (\app\models\Order::$sendWay as $i=>$send):?>
							<tr <?=($i==1)?'class="cur"':''?>>
								<td>
									<input value=<?=$i?> type="radio" name="delivery"/><?=$send['name']?>
								</td>
								<td>￥<?php printf('%.2f',$send['price'])?></td>
								<td><?=$send['intro']?></td>
							</tr>
                            <?php endforeach;?>
						</tbody>
					</table>

				</div>
			</div> 
			<!-- 配送方式 end --> 

			<!-- 支付方式  start-->
			<div class="pay">
				<h3>支付方式 </h3>
				<div class="pay_select">
					<table>
                        <?php foreach (\app\models\Order::$payWay as $i=>$pay):?>
						<tr <?=($i==1)?'class="cur"':''?>>
							<td class="col1"><input type="radio" name="pay" value="<?=$i?>" /><?=$pay['name']?></td>
							<td class="col2"><?=$pay['intro']?></td>
						</tr>
                        <?php endforeach;?>
					</table>

				</div>
			</div>
			<!-- 支付方式  end-->

			<!-- 发票信息 start-->
			<div class="receipt none">
				<h3>发票信息 </h3>


				<div class="receipt_select ">
						<ul>
							<li>
								<label for="">发票抬头：</label>
								<input type="radio" name="type" checked="checked" class="personal" />个人
								<input type="radio" name="type" class="company"/>单位
								<input type="text" class="txt company_input" disabled="disabled" />
							</li>
							<li>
								<label for="">发票内容：</label>
								<input type="radio" name="content" checked="checked" />明细
								<input type="radio" name="content" />办公用品
								<input type="radio" name="content" />体育休闲
								<input type="radio" name="content" />耗材
							</li>
						</ul>
				</div>
			</div>
			<!-- 发票信息 end-->

			<!-- 商品清单 start -->
			<div class="goods">
				<h3>商品清单</h3>
				<table>
					<thead>
						<tr>
							<th class="col1">商品</th>
							<th class="col3">价格</th>
							<th class="col4">数量</th>
							<th class="col5">小计</th>
						</tr>	
					</thead>
					<tbody>
                    <?php foreach ($goodss as $goods):?>
                    <tr data-id="<?=$goods->id?>">
                        <td class="col1"><a href="<?=Url::to(['index/goods','id'=>$goods->id])?>"><img src="<?=$goods->logo?>" alt="" /></a>  <strong><a href=""><?=$goods->name?></a></strong></td>
                        <td class="col3">￥<span><?=$goods->shop_price?></span></td>
                        <td class="col4">
                            <?=$cookies[$goods->id]?>
                        </td>
                        <td class="col5 total" >￥<span>
                        <?php
                        $temp=$goods->shop_price*$cookies[$goods->id];
                        $price+=$temp;
                        printf('%.2f',$temp);
                        ?></span></td>
                    <?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<ul>
									<li>
										<span><?=count($cookies)?> 件商品，总商品金额：</span>
										<em id="pay">
                                            ￥<?php
                                            printf('%.2f',$price);
                                            ?>
                                        </em>
									</li>
									<li>
										<span>返现：</span>
										<em id="repay">-￥0.00</em>
									</li>
									<li>
										<span>运费：</span>
										<em id="send">￥</em>
									</li>
									<li>
										<span>应付总额：</span>
										<em id="total">￥0.00</em>
									</li>
								</ul>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
        </div>
            <!-- 商品清单 end -->
		<div class="fillin_ft">
            <a href="javascript:void(0)" id="submit"><span>提交订单</span></a>
			<p>应付总额：<strong id="totalyuan">￥0.00元</strong></p>
		</div>
        </form>
	</div>
	<!-- 主体部分 end -->
