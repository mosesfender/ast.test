<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\modules\rest\controllers;

use app\components\View;
use JetBrains\PhpStorm\ArrayShape;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * Базовый контроллер для REST. От него наследуются все контроллеры сущностей.
 */
class BaseRestController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => HttpBearerAuth::class,
//        ];
        return $behaviors;
    }
    
    public function renderActiveFormData($view, $params)
    {
        $this->getView()->canRenderJSScripts = false;
        return [
            'html' => $this->renderAjax($view, $params),
            'js'   => $this->view->validationScript,
        ];
    }
    
    /**
     * @return View
     */
    public function getView(): View
    {
        return parent::getView();
    }
}
