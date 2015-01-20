<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules'=> [
                'GET,HEAD,POST api/v1/<controller:\w+>/method/<method:\w+>' => 'api/<controller>/<method>',
                'GET,HEAD,POST api/v1/<controller:\w+>/method/<method:\w+>/<id:\w+>' => 'api/<controller>/<method>',
                'PUT,PATCH api/v1/<controller:\w+>/<id:\w+>' => 'api/<controller>/update',
                'DELETE api/v1/<controller:\w+>' => 'api/<controller>/delete_all',
                'DELETE api/v1/<controller:\w+>/<id:\w+>' => 'api/<controller>/delete',
                'POST api/v1/<controller:\w+>/' => 'api/<controller>/create',
                'GET,HEAD api/v1/<controller:\w+>' => 'api/<controller>/list',
                'GET,HEAD api/v1/<controller:\w+>/<id:\w+>' => 'api/<controller>/read',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'wTXeoTXAWai2P70mizluA0-fxFMG5t-u',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = 'yii\debug\Module';
//
//    $config['bootstrap'][] = 'gii';
//    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
