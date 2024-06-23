<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\components;

use yii\helpers\Json;
use yii\widgets\ActiveFormAsset;

class ActiveForm extends \yii\bootstrap5\ActiveForm
{
    /**
     * Перекрываем метод для удобства. Здесь мы убираем регистрацию бандлов для активной формы и валидации,
     * предполагая, что будем их загружать в основном шаблоне приложения. Так же убираем регистрацию скриптов валидации,
     * одновременно присваивая их переменной объекта View, чтобы удачно передать их в JSON'е.
     * @see \app\components\View::$validationScript Скрипты клиентской валидации полей формы
     * @see \app\components\View::$canRenderJSScripts Флаг отключающий добавление скриптов из бандлов
     * @see \app\components\View::renderBodyEndHtml Перекрытие метода рендеринга низа тела документа
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $options = Json::htmlEncode($this->getClientOptions());
        $attributes = Json::htmlEncode($this->attributes);
        /* @var View $view */
        $view = $this->getView();
        //ActiveFormAsset::register($view);
        //$view->registerJs("jQuery('#$id').yiiActiveForm($attributes, $options);");
        $view->validationScript = "jQuery('#$id').yiiActiveForm($attributes, $options);";
    }
    
}
