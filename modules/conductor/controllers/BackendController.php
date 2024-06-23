<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\modules\conductor\controllers;

class BackendController extends BaseWebController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}