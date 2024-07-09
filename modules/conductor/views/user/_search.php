<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\models\User;
use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\models\UserSearch;

/**
 * @var View       $this
 * @var UserSearch $model
 * @var ActiveForm $form
 */
?>

<div class="user-search text-center mb-4">
    <?php $form = ActiveForm::begin(
        [
            'action'                 => ['index'],
            'method'                 => 'get',
            'options'                => [
                'data-pjax' => 1
            ],
            'enableClientValidation' => false,
            'layout'                 => ActiveForm::LAYOUT_FLOATING
        ]
    ); ?>
    <div class="row">
        <div class="col-1">
            <?= $form->field($model, 'id')
                ->textInput(['autocomplete' => 'off'])
                ->label('ID') ?>
        </div>
        <div class="col-8">
            <?= $form->field($model, 'usernameOrEmail')
                ->textInput(['autocomplete' => 'off'])
                ->label(\yii::t('app', 'Full name, username or email')) ?>
        </div>
        <div class="col-3 mt-3">
            <input type="checkbox" class="btn-check" name="UserSearch[inTrash]" id="user-in-trash-flg"
                   value="<?= User::FLG_DELETED ?>"
                   autocomplete="off" <?= $model->inTrash & User::FLG_DELETED ? 'checked' : '' ?>>
            <label class="btn btn-lg btn-outline-primary floating"
                   for="user-in-trash-flg"
                   data-toggle="tooltip" title="Find trash">
                <span class="si si-find-in-trash"></span><?= \yii::t('app', 'In trash') ?>
            </label>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="input-group" role="group">
                <div class="input-group-text"><?= \yii::t('app', 'User roles'); ?></div>
                <?php $allRolesEnable = empty($model->role); ?>
                <?php foreach (User::roleList() as $key => $role) { ?>
                    <input type="checkbox" class="btn-check" name="UserSearch[role][]" id="user-role-<?= $key ?>"
                           value="<?= $key ?>"
                           autocomplete="off"
                        <?= $allRolesEnable || $model->role & $key ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-outline-primary floating"
                           for="user-role-<?= $key ?>"><?= \yii::t('app', $role) ?></label>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <div class="btn-group text-center mt-3">
        <?= Html::submitButton(\yii::t('app', 'Search'), ['class' => 'btn btn-sm btn-primary']) ?>
        <?= Html::resetButton(\yii::t('app', 'Reset'), ['class' => 'btn btn-sm btn-outline-secondary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
