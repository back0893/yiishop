<?php
/**
 * @var $this \yii\web\View
 */
$this->title='登录商城';
$this->registerCssFile('/style/login.css');
?>

	<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<!-- 登录主体部分start -->
	<div class="login w990 bc mt10">
		<div class="login_hd">
			<h2>用户登录</h2>
			<b></b>
		</div>
		<div class="login_bd">
			<div class="login_form fl">
				<form action="" method="post" id="loginForm">
                    <input type="hidden" name="_csrf-frontend" value="<?=\Yii::$app->request->csrfToken?>"/>
					<ul>
						<li>
							<label for="">用户名：</label>
							<input type="text" class="txt" name="username" />
						</li>
						<li>
							<label for="">密码：</label>
							<input type="password" class="txt" name="password" />
							<a href="">忘记密码?</a>
						</li>
						<li class="checkcode">
							<label for="">验证码：</label>
							<input type="text"  name="checkcode" />
							<img alt="" id="captcha"/>
							<span>看不清？<a href="javascript:void(0)" id='change_captcha'>换一张</a></span>
						</li>
						<li>
							<label for="">&nbsp;</label>
							<input type="checkbox" class="chb"  name="remember" /> 保存登录信息
						</li>
						<li>
							<label for="">&nbsp;</label>
							<input type="submit" value="" class="login_btn" />
						</li>
					</ul>
				</form>

				<div class="coagent mt15">
					<dl>
						<dt>使用合作网站登录商城：</dt>
						<dd class="qq"><a href=""><span></span>QQ</a></dd>
						<dd class="weibo"><a href=""><span></span>新浪微博</a></dd>
						<dd class="yi"><a href=""><span></span>网易</a></dd>
						<dd class="renren"><a href=""><span></span>人人</a></dd>
						<dd class="qihu"><a href=""><span></span>奇虎360</a></dd>
						<dd class=""><a href=""><span></span>百度</a></dd>
						<dd class="douban"><a href=""><span></span>豆瓣</a></dd>
					</dl>
				</div>
			</div>
			
			<div class="guide fl">
				<h3>还不是商城用户</h3>
				<p>现在免费注册成为商城用户，便能立刻享受便宜又放心的购物乐趣，心动不如行动，赶紧加入吧!</p>

				<a href="<?=\yii\helpers\Url::to(['memeber/register'])?>" class="reg_btn">免费注册 >></a>
			</div>

		</div>
	</div>
	<!-- 登录主体部分end -->
    <script type="text/javascript" src=""></script>
<?php
    $this->registerJsFile('/js/dist/jquery.validate.min.js',['depends'=>'yii\web\JqueryAsset']);
    $this->registerJsFile('/js/dist/localization/messages_zh.js',['depends'=>'yii\web\JqueryAsset']);
    $this->registerJsFile('myjs/login.js',['depends'=>'yii\web\JqueryAsset']);
?>
    <script type="text/javascript">
        var token='<?=\Yii::$app->request->csrfToken?>';
    </script>
