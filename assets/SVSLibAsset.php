<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\assets;

use yii\web\AssetBundle;

class SVSLibAsset extends AssetBundle
{
    public $sourcePath = "@app/assets/dist/common";
    public $css = [];
    public $js = ['svslib.js'];
    public $depends = [];
    public $publishOptions = ['forceCopy' => YII_ENV == YII_ENV_DEV];
}


