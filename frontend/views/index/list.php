<?php
/**
 * @var $this \yii\web\View
 */
use yii\helpers\Url;

$this->title='商品列表页';
$this->registerCssFile('/style/list.css');
$this->registerCssFile('/style/common.css');
$this->registerCssFile('/style/bottomnav.css');
$this->registerJsFile('/js/header.js',['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/js/list.js',['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/myjs/list.js',['depends'=>'yii\web\JqueryAsset']);
$request=Yii::$app->request;
?>
	<!-- 头部 start -->
	<div class="header w1210 bc mt15">
		<!-- 头部上半部分 start 包括 logo、搜索、用户中心和购物车结算 -->
		<?=$this->render('search')?>
		<!-- 头部上半部分 end -->
		
		<div style="clear:both;"></div>

		<!-- 导航条部分 start -->
		<div class="nav w1210 bc mt10">
			<!--  商品分类部分 start-->
			<div class="category fl cat1"> <!-- 非首页，需要添加cat1类 -->
				<div class="cat_hd off">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，并将cat_bd设置为不显示，鼠标滑过时展开菜单则将off类换成on类 -->
					<h2>全部商品分类</h2>
					<em></em>
				</div>

                <?=$this->render('catbd')?>

			</div>
			<!--  商品分类部分 end--> 

			<div class="navitems fl">
				<ul class="fl">
					<li class="current"><a href="">首页</a></li>
					<li><a href="">电脑频道</a></li>
					<li><a href="">家用电器</a></li>
					<li><a href="">品牌大全</a></li>
					<li><a href="">团购</a></li>
					<li><a href="">积分商城</a></li>
					<li><a href="">夺宝奇兵</a></li>
				</ul>
				<div class="right_corner fl"></div>
			</div>
		</div>
		<!-- 导航条部分 end -->
	</div>
	<!-- 头部 end-->

	<div style="clear:both;"></div>

	<!-- 列表主体 start -->
	<div class="list w1210 bc mt10">
		<!-- 面包屑导航 start -->
		<div class="breadcrumb">
			<h2>当前位置：<a href="">首页</a> > <a href="">电脑、办公</a></h2>
		</div>
		<!-- 面包屑导航 end -->

		<!-- 左侧内容 start -->
		<div class="list_left fl mt10">
			<!-- 分类列表 start -->
			<div class="catlist">
				<h2>电脑、办公</h2>
				<div class="catlist_wrap">
					<div class="child">
						<h3 class="on"><b></b>电脑整机</h3>
						<ul>
							<li><a href="">笔记本</a></li>
							<li><a href="">超极本</a></li>
							<li><a href="">平板电脑</a></li>
						</ul>
					</div>

					<div class="child">
						<h3><b></b>电脑配件</h3>
						<ul class="none">
							<li><a href="">CPU</a></li>
							<li><a href="">主板</a></li>
							<li><a href="">显卡</a></li>
						</ul>
					</div>

					<div class="child">
						<h3><b></b>办公打印</h3>
						<ul class="none">
							<li><a href="">打印机</a></li>
							<li><a href="">一体机</a></li>
							<li><a href="">投影机</a></li>
							</li>
						</ul>
					</div>

					<div class="child">
						<h3><b></b>网络产品</h3>
						<ul class="none">
							<li><a href="">路由器</a></li>
							<li><a href="">网卡</a></li>
							<li><a href="">交换机</a></li>
							</li>
						</ul>
					</div>

					<div class="child">
						<h3><b></b>外设产品</h3>
						<ul class="none">
							<li><a href="">鼠标</a></li>
							<li><a href="">键盘</a></li>
							<li><a href="">U盘</a></li>
						</ul>
					</div>
				</div>
				
				<div style="clear:both; height:1px;"></div>
			</div>
			<!-- 分类列表 end -->
				
			<div style="clear:both;"></div>	

			<!-- 新品推荐 start -->
			<div class="newgoods leftbar mt10">
				<h2><strong>新品推荐</strong></h2>
				<div class="leftbar_wrap">
					<ul>
						<li>
							<dl>
								<dt><a href=""><img src="/images/list_hot1.jpg" alt="" /></a></dt>
								<dd><a href="">美即流金丝语悦白美颜新年装4送3</a></dd>
								<dd><strong>￥777.50</strong></dd>
							</dl>
						</li>

						<li>
							<dl>
								<dt><a href=""><img src="/images/list_hot2.jpg" alt="" /></a></dt>
								<dd><a href="">领券满399减50 金斯利安多维片</a></dd>
								<dd><strong>￥239.00</strong></dd>
							</dl>
						</li>

						<li class="last">
							<dl>
								<dt><a href=""><img src="/images/list_hot3.jpg" alt="" /></a></dt>
								<dd><a href="">皮尔卡丹pierrecardin 男士长...</a></dd>
								<dd><strong>￥1240.50</strong></dd>
							</dl>
						</li>
					</ul>
				</div>
			</div>
			<!-- 新品推荐 end -->

			<!--热销排行 start -->
			<div class="hotgoods leftbar mt10">
				<h2><strong>热销排行榜</strong></h2>
				<div class="leftbar_wrap">
					<ul>
						<li></li>
					</ul>
				</div>
			</div>
			<!--热销排行 end -->

			<!-- 最近浏览 start -->
			<div class="viewd leftbar mt10">
				<h2><a href="">清空</a><strong>最近浏览过的商品</strong></h2>
				<div class="leftbar_wrap">
					<dl>
						<dt><a href=""><img src="/images/hpG4.jpg" alt="" /></a></dt>
						<dd><a href="">惠普G4-1332TX 14英寸笔记...</a></dd>
					</dl>

					<dl class="last">
						<dt><a href=""><img src="/images/crazy4.jpg" alt="" /></a></dt>
						<dd><a href="">直降200元！TCL正1.5匹空调</a></dd>
					</dl>
				</div>
			</div>
			<!-- 最近浏览 end -->
		</div>
		<!-- 左侧内容 end -->
	
		<!-- 列表内容 start -->
		<div class="list_bd fl ml10 mt10">
			<!-- 热卖、促销 start -->
			<div class="list_top">
				<!-- 热卖推荐 start -->
				<div class="hotsale fl">
					<h2><strong><span class="none">热卖推荐</span></strong></h2>
					<ul>
						<li>
							<dl>
								<dt><a href=""><img src="/images/hpG4.jpg" alt="" /></a></dt>
								<dd class="name"><a href="">惠普G4-1332TX 14英寸笔记本电脑 （i5-2450M 2G 5</a></dd>
								<dd class="price">特价：<strong>￥2999.00</strong></dd>
								<dd class="buy"><span>立即抢购</span></dd>
							</dl>
						</li>

						<li>
							<dl>
								<dt><a href=""><img src="/images/list_hot3.jpg" alt="" /></a></dt>
								<dd class="name"><a href="">ThinkPad E42014英寸笔记本电脑</a></dd>
								<dd class="price">特价：<strong>￥4199.00</strong></dd>
								<dd class="buy"><span>立即抢购</span></dd>
							</dl>
						</li>

						<li>
							<dl>
								<dt><a href=""><img src="/images/acer4739.jpg" alt="" /></a></dt>
								<dd class="name"><a href="">宏碁AS4739-382G32Mnkk 14英寸笔记本电脑</a></dd>
								<dd class="price">特价：<strong>￥2799.00</strong></dd>
								<dd class="buy"><span>立即抢购</span></dd>
							</dl>
						</li>
					</ul>
				</div>
				<!-- 热卖推荐 end -->

				<!-- 促销活动 start -->
				<div class="promote fl">
					<h2><strong><span class="none">促销活动</span></strong></h2>
					<ul>
						<li><b>.</b><a href="">DIY装机之向雷锋同志学习！</a></li>
						<li><b>.</b><a href="">京东宏碁联合促销送好礼！</a></li>
						<li><b>.</b><a href="">台式机笔记本三月巨惠！</a></li>
						<li><b>.</b><a href="">富勒A53g智能人手识别鼠标</a></li>
						<li><b>.</b><a href="">希捷硬盘白色情人节专场</a></li>
					</ul>

				</div>
				<!-- 促销活动 end -->
			</div>
			<!-- 热卖、促销 end -->
			
			<div style="clear:both;"></div>
			
			<!-- 商品筛选 start -->
			<div class="filter mt10">
				<h2><a href="">重置筛选条件</a> <strong>商品筛选</strong></h2>
				<div class="filter_wrap">
					<dl>
						<dt>品牌：</dt>
						<dd class="cur"><a href="">不限</a></dd>
						<dd><a href="">联想（ThinkPad）</a></dd>
						<dd><a href="">联想（Lenovo）</a></dd>
						<dd><a href="">宏碁（acer）</a></dd>
						<dd><a href="">华硕（ASUS）</a></dd>
						<dd><a href="">戴尔（DELL）</a></dd>
						<dd><a href="">索尼（SONY）</a></dd>
						<dd><a href="">惠普（HP）</a></dd>
						<dd><a href="">三星（SAMSUNG）</a></dd>
						<dd><a href="">优派（ViewSonic）</a></dd>
						<dd><a href="">苹果（Apple）</a></dd>
						<dd><a href="">富士通（Fujitsu）</a></dd>
					</dl>

					<dl>
						<dt>价格：</dt>
						<dd class="cur"><a href="">不限</a></dd>
						<dd><a href="">1000-1999</a></dd>
						<dd><a href="">2000-2999</a></dd>
						<dd><a href="">3000-3499</a></dd>
						<dd><a href="">3500-3999</a></dd>
						<dd><a href="">4000-4499</a></dd>
						<dd><a href="">4500-4999</a></dd>
						<dd><a href="">5000-5999</a></dd>
						<dd><a href="">6000-6999</a></dd>
						<dd><a href="">7000-7999</a></dd>
					</dl>

					<dl>
						<dt>尺寸：</dt>
						<dd class="cur"><a href="">不限</a></dd>
						<dd><a href="">10.1英寸及以下</a></dd>
						<dd><a href="">11英寸</a></dd>
						<dd><a href="">12英寸</a></dd>
						<dd><a href="">13英寸</a></dd>
						<dd><a href="">14英寸</a></dd>
						<dd><a href="">15英寸</a></dd>
					</dl>

					<dl class="last">
						<dt>处理器：</dt>
						<dd class="cur"><a href="">不限</a></dd>
						<dd><a href="">intel i3</a></dd>
						<dd><a href="">intel i5</a></dd>
						<dd><a href="">intel i7</a></dd>
						<dd><a href="">AMD A6</a></dd>
						<dd><a href="">AMD A8</a></dd>
						<dd><a href="">AMD A10</a></dd>
						<dd><a href="">其它intel平台</a></dd>
					</dl>
				</div>
			</div>
			<!-- 商品筛选 end -->
			
			<div style="clear:both;"></div>

			<!-- 排序 start -->
			<div class="sort mt10">
				<dl>
					<dt>排序：</dt>
					<dd class="cur"><a href="">销量</a></dd>
					<dd><a href="">价格</a></dd>
					<dd><a href="">评论数</a></dd>
					<dd><a href="">上架时间</a></dd>
				</dl>
			</div>
			<!-- 排序 end -->
			
			<div style="clear:both;"></div>

			<!-- 商品列表 start-->
			<div class="goodslist mt10">
				<ul>
                    <?php foreach ($goods as $good):?>
                        <li>
                            <dl>
                                <dt><a href="<?=Url::to(['index/goods','id'=>$good['id']])?>"><img style="width: 160px;height: 160px;" src="<?=$good['logo'];?>" alt="<?=$good['name']?>"></a></dt>
                                <dd><a href="<?=Url::to(['index/goods','id'=>$good['id']])?>"><?=$good['name']?></dd>
                                <dd><strong><?=$good['market_price']?></strong></dd>
                                <dd><a href="<?=Url::to(['index/goods','id'=>$good['id']])?>"><em>浏览次数:<?=$good['view']?></em></a></dd>
                            </dl>
                        </li>
                    <?php endforeach;?>
				</ul>
			</div>
			<!-- 商品列表 end-->

			<!-- 分页信息 start -->
            <div class="mt20">
            </div>
			<div class="page mt20">
                <?php
                    $cates=$request->get('cates',1);
                    $currentPage=$request->get('page',1);
                    $pre=(($currentPage-1)<1)?1:($currentPage-1);
                    $next=(($currentPage+1)>$paginate)?$paginate:$currentPage+1;
                    $keyWord=$request->get('keyWord','');
                ?>
				<a href="<?=Url::to(['list','cates'=>$cates,'page'=>1,'keyWord'=>$keyWord])?>">首页</a>
				<a href="<?=Url::to(['list','cates'=>$cates,'page'=>$pre,'keyWord'=>$keyWord])?>">上一页</a>
				<?php for($page=1;$page<=$paginate;$page++):?>
                    <a href="<?=Url::to(['list','cates'=>$cates,'page'=>$page,'keyWord'=>$keyWord])?>" <?=($currentPage==$page)?'class="cur"':''?> ><?=$page?></a>
                <?php endfor?>
				<a href="<?=Url::to(['list','cates'=>$cates,'page'=>$next,'keyWord'=>$keyWord])?>">下一页</a>
				<a href="<?=Url::to(['list','cates'=>$cates,'page'=>$paginate,'keyWord'=>$keyWord])?>">尾页</a>&nbsp;&nbsp;
				<span>
					<em>共<?=$paginate?>页&nbsp;&nbsp;到第 <input type="text" class="page_num" value="1"/> 页</em>
					<a class="skipsearch" id="skipsearch" href="javascript:void(0);">确定</a>
				</span>
			</div>
			<!-- 分页信息 end -->

		</div>
		<!-- 列表内容 end -->
	</div>
	<!-- 列表主体 end-->

	<div style="clear:both;"></div>
	<!-- 底部导航 start -->
	<div class="bottomnav w1210 bc mt20">
		<div class="bnav1">
			<h3><b></b> <em>购物指南</em></h3>
			<ul>
				<li><a href="">购物流程</a></li>
				<li><a href="">会员介绍</a></li>
				<li><a href="">团购/机票/充值/点卡</a></li>
				<li><a href="">常见问题</a></li>
				<li><a href="">大家电</a></li>
				<li><a href="">联系客服</a></li>
			</ul>
		</div>
		
		<div class="bnav2">
			<h3><b></b> <em>配送方式</em></h3>
			<ul>
				<li><a href="">上门自提</a></li>
				<li><a href="">快速运输</a></li>
				<li><a href="">特快专递（EMS）</a></li>
				<li><a href="">如何送礼</a></li>
				<li><a href="">海外购物</a></li>
			</ul>
		</div>

		
		<div class="bnav3">
			<h3><b></b> <em>支付方式</em></h3>
			<ul>
				<li><a href="">货到付款</a></li>
				<li><a href="">在线支付</a></li>
				<li><a href="">分期付款</a></li>
				<li><a href="">邮局汇款</a></li>
				<li><a href="">公司转账</a></li>
			</ul>
		</div>

		<div class="bnav4">
			<h3><b></b> <em>售后服务</em></h3>
			<ul>
				<li><a href="">退换货政策</a></li>
				<li><a href="">退换货流程</a></li>
				<li><a href="">价格保护</a></li>
				<li><a href="">退款说明</a></li>
				<li><a href="">返修/退换货</a></li>
				<li><a href="">退款申请</a></li>
			</ul>
		</div>

		<div class="bnav5">
			<h3><b></b> <em>特色服务</em></h3>
			<ul>
				<li><a href="">夺宝岛</a></li>
				<li><a href="">DIY装机</a></li>
				<li><a href="">延保服务</a></li>
				<li><a href="">家电下乡</a></li>
				<li><a href="">京东礼品卡</a></li>
				<li><a href="">能效补贴</a></li>
			</ul>
		</div>
	</div>
	<!-- 底部导航 end -->