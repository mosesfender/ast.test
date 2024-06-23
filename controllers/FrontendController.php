<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\controllers;

use yii\web\Controller;

class FrontendController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
