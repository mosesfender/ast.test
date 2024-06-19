<?php

$result = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=astdb',
    'username' => 'ast',
    'password' => 'alskdjfhg',
    'charset' => 'utf8',

];

if (YII_ENV == YII_ENV_PROD) {
    $result = array_merge($result, [
        'enableSchemaCache' => true,
        'schemaCacheDuration' => 3600,
        'schemaCache' => 'cache',
    ]);
}

return $result;
