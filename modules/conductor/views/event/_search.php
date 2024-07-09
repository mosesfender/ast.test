<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\models\Event;
use app\models\EventSearch;
use app\models\User;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap5\ActiveForm;

/**
 * @var View        $this
 * @var EventSearch $model
 * @var ActiveForm  $form
 */
\kartik\icons\FontAwesomeAsset::register($this);
?>

<div class="event-search">
    
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
        <div class="col-4">
            <?= $form->field($model, 'title')->textInput(['autocomplete' => 'off']) ?>
        </div>
        <div class="col-4">
            <?= $form->field($model, 'description')->textInput(['autocomplete' => 'off']) ?>
        </div>
        <div class="col-3 mt-3">
            <input type="checkbox" class="btn-check" name="EventSearch[inTrash]" id="user-in-trash-flg"
                   value="<?= Event::ME_DELETED ?>"
                   autocomplete="off" <?= $model->inTrash ? 'checked' : '' ?>>
            <label class="btn btn-lg btn-outline-primary floating" for="user-in-trash-flg">
                <span class="si si-find-in-trash"></span><?= \yii::t('app', 'In trash') ?>
            </label>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="input-group" role="group">
            <div class="input-group-text"><?= \yii::t('app', 'Begin time'); ?></div>
            
            <input type="checkbox" class="btn-check" name="EventSearch[beginTimePast]" id="event-past-btn"
                   value="past"
                   autocomplete="off" <?= $model->beginTimePast ? 'checked' : '' ?>>
            <label class="btn btn-outline-primary"
                   for="event-past-btn"><?= \yii::t('app', 'Past events') ?></label>
            
            <input type="checkbox" class="btn-check" name="EventSearch[beginTimeFuture]" id="event-future-btn"
                   value="future"
                   autocomplete="off" <?= $model->beginTimeFuture ? 'checked' : '' ?>>
            <label class="btn btn-outline-primary"
                   for="event-future-btn"><?= \yii::t('app', 'Future events') ?></label>
            
            <div class="">
                <?= DateTimePicker::widget(
                    [
                        'model'         => $model,
                        'attribute'     => 'beginTimeStart',
                        'options'       => ['placeholder' => \yii::t('app', 'Start time from...')],
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ); ?>
            </div>
            <div class="">
                <?= DateTimePicker::widget(
                    [
                        'model'         => $model,
                        'attribute'     => 'beginTimeEnd',
                        'options'       => ['placeholder' => \yii::t('app', 'Ending with time...')],
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ); ?>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="input-group">
                <?php
                echo $form->field($model, 'authors')->widget(Select2::class, [
                    'data' => ArrayHelper::map(User::find()
                                                   ->select(['user.id', 'fullname'])
                                                   ->nameSpelling()
                                                   ->indexBy('id')
                                                   ->asArray()
                                                   ->orderBy('fullname')
                                                   ->all(), 'id', 'fullname'),
                    
                    'options'       => ['placeholder' => \yii::t('app', 'Select authors...'), 'multiple' => true],
                    'pluginOptions' => [
                        'tags'            => true,
                        'tokenSeparators' => [',', ' '],
                    ],
                ])->label(false);
                ?>
            </div>
        </div>
    </div>
    
    <div class="btn-group text-center mt-3">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-sm btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-sm btn-outline-secondary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
