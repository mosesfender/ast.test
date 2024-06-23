<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id'           => 'ast_test',
    'name'         => 'АСТ тест',
    'language'     => 'ru-RU',
    'basePath'     => dirname(__DIR__),
    'bootstrap'    => ['log'],
    'defaultRoute' => 'frontend/index',
    'aliases'      => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components'   => [
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'zuqwJ_Mw8fTOgZY8TQhVH1-9_C5YeMvO',
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'user'         => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer'       => [
            'class'            => \yii\symfonymailer\Mailer::class,
            'viewPath'         => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db'           => $db,
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                '<act:\w+>'           => 'frontend/<act>',
                'conductor/<act:\w+>' => 'conductor/backend/<act>',
            ],
        ],
        'view' => [
            'class' => 'app\components\View'
        ]
    ],
    'modules'      => [
        'conductor' => [
            'class' => 'app\modules\conductor\Module',
        ],
        'rest'      => [
            'class' => 'app\modules\rest\Module',
        ],
    ],
    'params'       => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//        // uncomment the following to add your IP if you are not connecting from localhost.
//        //'allowedIPs' => ['127.0.0.1', '::1'],
//    ];
    
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

function prer($arr, $dump = false, $die = false)
{
    $trace = debug_backtrace();
    $cnt = count($trace);
    try {
        $_ = $cnt > 1 ? "{$trace[1]["class"]}::{$trace[1]["function"]}" : "";
        echo "<em style=\"color: #008de5;\">[{$trace[0]["line"]}] {$_}</em>";
    } catch (Exception $ex) {
    
    }
    echo "<pre>";
    $dump ? var_dump($arr) : print_r($arr);
    echo "</pre>";
    $die ? die(sprintf("Stoped on %s", date("d:m:Y H:i:s"))) : null;
}

function ql(\yii\db\Query $query, $die = false)
{
    prer($query->createCommand()->rawSql, 0, $die);
}