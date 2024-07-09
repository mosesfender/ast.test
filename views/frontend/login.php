<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\LoginForm;
use yii\web\View;

/**
 * @var View       $this
 * @var ActiveForm $form
 * @var LoginForm  $model
 */


$this->title = \yii::t('app', 'Login');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= \yii::t('app', 'Please fill out the following fields to login:') ?></p>

    <div class="row">
        <div class="col-lg-12">
            
            <?php $form = ActiveForm::begin(
                [
                    'id'          => 'login-form',
                    'fieldConfig' => [
                        'template'     => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'col-form-label mr-lg-3'],
                        'inputOptions' => ['class' => 'form-control'],
                        'errorOptions' => ['class' => 'invalid-feedback'],
                    ],
                ]); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'password')->passwordInput() ?>
                </div>
            </div>
            <?= $form->field($model, 'rememberMe')
                ->checkbox([
                               'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                           ]) ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton(\yii::t('app', 'Login'),
                                           ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
