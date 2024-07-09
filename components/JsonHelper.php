<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\components;

use yii\helpers\Json;

class JsonHelper extends Json
{
    /**
     * @param string $js
     *
     * @return string
     */
    public static function JSCompress(string $js): string
    {
        return preg_replace('/\x20{2,}|[\n\r\t]/', ' ', $js);
    }
}
