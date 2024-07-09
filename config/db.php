<?php

$result = [
    'class' => 'yii\db\Connection',
    'dsn' => '',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
    'on afterOpen' => function($event){
        $event->sender->createCommand("SET time_zone = 'Europe/Moscow'")->execute();
    }
];

if (YII_ENV == YII_ENV_PROD) {
    $result = array_merge($result, [
        'enableSchemaCache' => true,
        'schemaCacheDuration' => 3600,
        'schemaCache' => 'cache',
    ]);
}

return $result;
