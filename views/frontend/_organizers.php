<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\models\User;
use yii\bootstrap5\Html;
use yii\web\View;

/**
 * @var View   $this
 * @var User[] $list
 */


echo Html::beginTag('div', ['class' => 'event-organizers']);
echo Html::tag('label', \yii::t('app', 'Organizers'));
echo Html::beginTag('ul');
foreach ($list as $user) {
    echo Html::tag('li', $user->fullname);
}
echo Html::endTag('ul');
echo Html::endTag('div');