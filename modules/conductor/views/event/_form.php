<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\models\Event;
use app\widgets\EventOrganizers\Organizers;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Event $model */
/** @var yii\widgets\ActiveForm $form */
\kartik\icons\FontAwesomeAsset::register($this);
?>

<div class="event-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <div class="col-5">
            <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-7">
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-6">
            <?= $form->field($model, 'begin_time')
                ->widget(DateTimePicker::class,
                         [
                             'pluginOptions' => [
                                 'autoclose' => true,
                             ]
                         ]
                ); ?>
            <div class="input-group" role="group">
                <div class="input-group-text"><?= \yii::t('app', 'Event status'); ?></div>
                <?php foreach (Event::flagList() as $key => $flag) { ?>
                    <input type="radio" class="btn-check" name="Event[flags]" id="event-flag-<?= $key ?>"
                           value="<?= $key ?>"
                           autocomplete="off"
                        <?= $model->flags & $key ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-outline-primary floating"
                           for="event-flag-<?= $key ?>"><?= $flag ?></label>
                <?php } ?>
            </div>
        </div>
        <div class="col-6">
            <label class="control-label"><?= \yii::t('app', 'Organizers') ?></label>
            <div class="org-list">
                <?= Organizers::widget(
                    [
                        'event' => $model
                    ]
                ); ?>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
