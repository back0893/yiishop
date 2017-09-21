<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'layout'=>'mime',
    'language'=>'zh-CN',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            //修改为我实现的User登录models
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
            'loginUrl'=>['user/login'],
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'rules' => [
                [
                    'pattern'=>'<controller:\w+>/<action:\w+>/<id:\d+>',
                    'route'=>'<controller>/<action>',
                    'defaults'=>['id'=>0]
                ]
            ],
        ],
    ],
    'params' => $params,
    //将行为附加到配置里,必须是以as name开始
    'as rbacFilter'=>[
        'class'=>\backend\components\RbacFilter::className(),
        'except'=>['user/login','user/logout','site/error','debug/*'],
    ]
];
