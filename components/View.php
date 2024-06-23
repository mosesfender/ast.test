<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\components;

use yii\helpers\Html;

/**
 * @property boolean $canRenderJSScripts
 */
class View extends \yii\web\View
{
    /**
     * Для components/ActiveForm.php. Он складывает сюда текст скрипта валидации полей.
     *
     * @var string|null
     */
    public ?string $validationScript;
    
    /**
     * Флаг. Если выключен, в конце документа не будут вставляться скрипты из бандлов.
     *
     * @default true
     * @see     \app\components\View::renderBodyEndHtml Перекрытый метод рендеринга конца документа
     * @var bool
     */
    public bool $canRenderJSScripts = true;
    
    protected function renderBodyEndHtml($ajaxMode)
    {
        $lines = [];
        
        if (!empty($this->jsFiles[self::POS_END]) && $this->canRenderJSScripts) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_END]);
        }
        
        if ($ajaxMode) {
            $scripts = [];
            if (!empty($this->js[self::POS_END])) {
                $scripts[] = implode("\n", $this->js[self::POS_END]);
            }
            if (!empty($this->js[self::POS_READY])) {
                $scripts[] = implode("\n", $this->js[self::POS_READY]);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $scripts[] = implode("\n", $this->js[self::POS_LOAD]);
            }
            if (!empty($scripts)) {
                $lines[] = Html::script(implode("\n", $scripts));
            }
        } else {
            if (!empty($this->js[self::POS_END])) {
                $lines[] = Html::script(implode("\n", $this->js[self::POS_END]));
            }
            if (!empty($this->js[self::POS_READY])) {
                $js = "jQuery(function ($) {\n" . implode("\n", $this->js[self::POS_READY]) . "\n});";
                $lines[] = Html::script($js);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $js = "jQuery(window).on('load', function () {\n" . implode("\n", $this->js[self::POS_LOAD]) . "\n});";
                $lines[] = Html::script($js);
            }
        }
        
        return empty($lines) ? '' : implode("\n", $lines);
    }
}
