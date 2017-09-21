<?php
/**
 * @var $this \yii\web\View;
 */
    $this->title='用户注册';
    $this->registerCssFile('/style/login.css');
?>
	<div style="clear:both;"></div>
	<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<!-- 登录主体部分start -->
	<div class="login w990 bc mt10 regist">
		<div class="login_hd">
			<h2>用户注册</h2>
			<b></b>
		</div>
		<div class="login_bd">
			<div class="login_form fl">
				<form action="" method="post" id="signupForm">
                    <input type="hidden" name="_csrf-frontend" value="<?=\Yii::$app->request->csrfToken?>"/>
					<ul>
						<li>
							<label for="">用户名：</label>
							<input type="text" class="txt" name="username" />
							<p>3-20位字符，可由中文、字母、数字和下划线组成</p>
						</li>
						<li>
							<label for="">密码：</label>
							<input type="password" class="txt" name="password" id="password" />
							<p>6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号</p>
						</li>
						<li>
							<label for="">确认密码：</label>
							<input type="password" class="txt" name="repassword" />
							<p> <span>请再次输入密码</p>
						</li>
						<li>
							<label for="">邮箱：</label>
							<input type="text" class="txt" name="email" />
							<p>邮箱必须合法</p>
						</li>
						<li>
							<label for="">手机号码：</label>
							<input type="text" class="txt" value="" name="tel" id="tel" placeholder=""/>
						</li>
						<li>
							<label for="">验证码：</label>
							<input type="text" class="txt" value="" placeholder="请输入短信验证码" name="code" id="code"/> <input type="button" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>
							
						</li>
						<li class="checkcode">
							<label for="">验证码：</label>
							<input type="text"  name="checkcode" />
							<img id='captcha_img' alt="" />
							<span>看不清？<a href="javascript:void(0)" id="change_captcha">换一张</a></span>
						</li>
						
						<li>
							<label for="">&nbsp;</label>
							<input type="checkbox" class="chb" checked="checked" /> 我已阅读并同意《用户注册协议》
						</li>
						<li>
							<label for="">&nbsp;</label>
							<input type="submit" value="" class="login_btn" />
						</li>
					</ul>
				</form>

				
			</div>
			
			<div class="mobile fl">
				<h3>手机快速注册</h3>			
				<p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
				<p><strong>1069099988</strong></p>
			</div>

		</div>
	</div>
	<!-- 登录主体部分end -->

	<script type="text/javascript">
        var token='<?=\Yii::$app->request->csrfToken?>';
        var get_captcha='<?=\yii\helpers\Url::to(['site/captcha'])?>';
        var send_sms='<?=\yii\helpers\Url::to(['location/send-sms'])?>';
        var check_sms='<?=\yii\helpers\Url::to(['location/check-sms'])?>';
	</script>
<?php
$this->registerJsFile('/js/dist/jquery.validate.min.js',['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/js/dist/localization/messages_zh.js',['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/myjs/register.js',['depends'=>'yii\web\JqueryAsset']);
