<?php
return [
    'components' => [
        'request' => [
            'class'=>\yii\web\Request::className(),
            'enableCookieValidation' => false,
        ],
        'response'=>[
            'class'=>\yii\web\Response::className(),
            'format'=>\yii\web\Response::FORMAT_JSON
        ],
    ],
    'defaultRoute' => 'api/index',
    'layout'=>false,
];