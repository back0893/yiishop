<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Html;

//导航条开始
NavBar::begin([
    'brandLabel' => '商城后台管理',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
//为导航条添加菜单
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => '登录', 'url' => ['/user/login']];
} else {
    $menuItems=\backend\models\Menu::getData();
    $menuItems[] = '<li>'
        . Html::beginForm(['/user/logout'], 'post')
        . Html::submitButton(
            'Logout (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';
    $menuItems[]=['label'=>'修改密码','url'=>['/user/change-password']];
}
//显示菜单
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
//导航菜单结束
NavBar::end();
