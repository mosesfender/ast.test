<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use yii\bootstrap5\Html;
use yii\web\View;

/**
 * @var View $this
 */

$this->title = Yii::t('app', 'Conductor');
$this->params['breadcrumbs'][] = Yii::t('app', 'Start');

?>
<div class="back-index">
    <div class="card" style="width: 18rem;">
        <img src="/img/users.jpg" class="card-img-top" alt="">
        <div class="card-body">
            <h5 class="card-title">Пользователи</h5>
            <p class="card-text">Управлять пользователями может только администратор</p>
            <?php if (!\yii::$app->user->can('user-man')): ?>
                <p class="p-2 text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-3">У вас нет
                    прав на управление пользователями.</p>
            <?php endif; ?>
            <p class="text-center">
                <a href="/conductor/user/index" class="btn btn-outline-secondary stretched-link">Перейти</a>
            </p>
        </div>
    </div>
    <div class="card" style="width: 18rem;">
        <img src="/img/events.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">Мероприятия</h5>
            <p class="card-text">Управлять мероприятиями могут пользователи с правами организаторов мероприятия</p>
            <?php if (!\yii::$app->user->can('event-man')): ?>
                <p class="p-2 text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-3">У вас нет
                    прав на управление мероприятиями, доступен только список и просмотр мероприятий.</p>
            <?php endif; ?>
            <p class="text-center">
                <a href="/conductor/event/index" class="btn btn-outline-secondary stretched-link">Перейти</a>
            </p>
        </div>
    </div>
</div>
