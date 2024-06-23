<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\assets;

use yii\web\AssetBundle;

class SVSDialogAsset extends AssetBundle
{
    public $sourcePath = "@app/assets/dist/dialog";
    public $css = ['dialog.css'];
    public $js = ['dialog.js'];
    public $depends = [SVSLibAsset::class];
    public $publishOptions = ['forceCopy' => YII_ENV == YII_ENV_DEV];
}


