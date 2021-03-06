<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'layout'=>'mime',
    'language'=>'zh-CN',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'loginUrl'=>['member/login'],
            'identityClass' => 'frontend\models\Member',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'on afterLogin'=>function($event){
                $login=$event->identity;
                $login->last_login_time=time();
                $login->last_login_ip=\Yii::$app->request->getUserIP();
                //这里不要开启验证,直接保存就好了
                $login->save(false);
                $event->sender->trigger('cookie2db');
            },
            'on cookie2db'=>[
                '\app\models\Cart',
                'cookie2db'
            ],
            'on myEvent'=>function($event){
              echo '这是我自己配置的myEvent事件<br>';
            },
            'as myBehaviors'=>[
                'class'=>\frontend\components\MyBehaviors::className(),
                'pro1'=>'粗话化属性1',
                'pro2'=>'粗话化属性2'
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix'=>'.html',
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
//    'modules' => [
//        'api' => [
//            'class' => 'frontend\modules\api\Module',
//        ],
//    ],
];
