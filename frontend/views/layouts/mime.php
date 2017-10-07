<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;
AppAsset::register($this);
$isGuest=Url::to(['member/is-guest'],true);
$logout=Url::to(['member/logout'],true);
$js=<<<JS
    $.getJSON("{$isGuest}",function(data){
        if(data.isGuest){
             $("#isGuest").html('<li id="isGuest">'+
                           '<a href="{$logout}">'+data.username+'安全退出</a></li>');
        }
    });
JS;
$this->registerJs($js);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <!-- 顶部导航 start -->
    <div class="topnav">
        <div class="topnav_bd w990 bc">
            <div class="topnav_left">

            </div>
            <div class="topnav_right fr">
                <ul>
                    <li id="isGuest">您好，欢迎来到京西！[<a href="<?=Url::to(['member/login'])?>">登录</a>] [<a href="<?=Url::to(['member/register'])?>">免费注册</a>] </li>
                    <li class="line">|</li>
                    <li><a href="<?=Url::to(['cart/order-info'])?>">我的订单</a></li>
                    <li class="line">|</li>
                    <li>客户服务</li>

                </ul>
            </div>
        </div>
    </div>
    <!-- 顶部导航 end -->
    <div class="container">
    </div>
<?php $this->beginBody() ?>
    <?= $content ?>
<footer class="footer">
    <div class="container">
        <div style="clear:both;"></div>
        <!-- 底部版权 start -->
        <div class="footer w1210 bc mt15">
            <p class="links">
                <a href="">关于我们</a> |
                <a href="">联系我们</a> |
                <a href="">人才招聘</a> |
                <a href="">商家入驻</a> |
                <a href="">千寻网</a> |
                <a href="">奢侈品网</a> |
                <a href="">广告服务</a> |
                <a href="">移动终端</a> |
                <a href="">友情链接</a> |
                <a href="">销售联盟</a> |
                <a href="">京西论坛</a>
            </p>
            <p class="copyright">
                © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
            </p>
            <p class="auth">
                <a href=""><img src="/images/xin.png" alt="" /></a>
                <a href=""><img src="/images/kexin.jpg" alt="" /></a>
                <a href=""><img src="/images/police.jpg" alt="" /></a>
                <a href=""><img src="/images/beian.gif" alt="" /></a>
            </p>
        </div>
        <!-- 底部版权 end -->
    </div>
</footer>
<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
