<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\modules\conductor;

use app\components\rbac\EventAuthorRule;
use yii\helpers\ArrayHelper;
use yii\rbac\PhpManager;

class Module extends \yii\base\Module
{
    public function init()
    {
        /* @var $man PhpManager */
//        $man = \yii::$app->authManager;
//        $eo = $man->getItem('user.update');
//        $rule = new EventAuthorRule();
//        $man->add($rule);
//        $eo->ruleName = $rule->name;
        
        parent::init();
        \yii::$app->defaultRoute = '/conductor/backend/index';
        $bc = \yii::$container->getDefinitions()['yii\bootstrap5\Breadcrumbs'];
        $bc['homeLink'] = ['label' => \yii::t('app', 'Conductor'), 'url' => ['/conductor']];
        \yii::$container->setDefinitions(['yii\bootstrap5\Breadcrumbs' => $bc]);
    }
}
