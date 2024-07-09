<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\models\User;
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap5\ActiveForm;

/** @var View $this */
/** @var User $model */
/** @var ActiveForm $form */
?>

<div class="user-form">
    
    <?php $form = ActiveForm::begin(
        [
        
        ]
    ); ?>
    <div class="row row-cols-3">
        <?= $form->field($model, 'second_name')->textInput(['autocomplete' => 'off']) ?>
        <?= $form->field($model, 'first_name')->textInput(['autocomplete' => 'off']) ?>
        <?= $form->field($model, 'sur_name')->textInput(['autocomplete' => 'off']) ?>
    </div>
    
    <div class="row <?= $model->scenario == User::SCENARIO_CREATE ? 'row-cols-3' : 'row-cols-2' ?>">
        <?= $form->field($model, 'username')->textInput(['autocomplete' => 'off']) ?>
        <?php
        if ($model->scenario == User::SCENARIO_CREATE)
            echo $form->field($model, 'password')->textInput(['autocomplete' => 'off'])
        ?>
        <div class="mb-3">
            <label class="form-label"><?= \yii::t('app', 'Role') ?></label>
            <div>
                <input type="radio" class="btn-check" name="User[role]" id="user-role-super"
                       value="<?= User::ROLE_SUPER ?>"
                       autocomplete="off" <?= $model->role & User::ROLE_SUPER ? 'checked' : '' ?>>
                <label class="btn btn-outline-primary"
                       for="user-role-super"><?= User::roleList(User::ROLE_SUPER) ?></label>
                
                <input type="radio" class="btn-check" name="User[role]" id="user-role-event-organizer"
                       value="<?= User::ROLE_EVENT_ORGANIZER ?>"
                       autocomplete="off" <?= $model->role & User::ROLE_EVENT_ORGANIZER ? 'checked' : '' ?>>
                <label class="btn btn-outline-primary"
                       for="user-role-event-organizer"><?= User::roleList(User::ROLE_EVENT_ORGANIZER) ?></label>
                
                <input type="radio" class="btn-check" name="User[role]" id="user-role-simple-user"
                       value="<?= User::ROLE_SIMPLE_USER ?>"
                       autocomplete="off" <?= $model->role & User::ROLE_SIMPLE_USER ? 'checked' : '' ?>>
                <label class="btn btn-outline-primary"
                       for="user-role-simple-user"><?= User::roleList(User::ROLE_SIMPLE_USER) ?></label>
            </div>
        </div>
    </div>
    <div class="row row-cols-2">
        <?= $form->field($model, 'email')->textInput(['autocomplete' => 'off']) ?>
        <?= $form->field($model, 'phone')->textInput(['autocomplete' => 'off']) ?>
    </div>
    
    <div class="row">
        <div class="col">
            <label class="form-label"><?= \yii::t('app', 'Flags') ?></label>
            <div class="mb-3">
                <input type="checkbox" class="btn-check" name="User[flags][]" id="user-flag-enabled"
                       value="<?= User::FLG_ENABLED ?>"
                       autocomplete="off" <?= $model->flags & User::FLG_ENABLED ? 'checked' : '' ?>>
                <label class="btn btn-outline-success"
                       for="user-flag-enabled"><?= User::flagList(User::FLG_ENABLED) ?></label>
                
                <input type="checkbox" class="btn-check" name="User[flags][]" id="user-flag-deleted"
                       value="<?= User::FLG_DELETED ?>"
                       autocomplete="off" <?= $model->flags & User::FLG_DELETED ? 'checked' : '' ?>>
                <label class="btn btn-outline-danger"
                       for="user-flag-deleted"><?= User::flagList(User::FLG_DELETED) ?></label>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
