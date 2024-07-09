<?php
/**
 * @link      https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/dist/site';
    public $css = ['site.css'];
    public $js = ['cookie.js', 'popper.min.js', 'index.js', 'spoiler.js'];
    public $depends
        = [
            'yii\web\YiiAsset',
            'yii\bootstrap5\BootstrapAsset',
            'yii\bootstrap5\BootstrapPluginAsset'
        ];
    public $publishOptions = ['forceCopy' => YII_ENV == YII_ENV_DEV];
}
