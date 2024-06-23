<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\modules\rest;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        /* Отключаем хранение аутентификационных данных в сессии */
        //\Yii::$app->user->enableSession = false;
    }
}