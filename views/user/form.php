<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\models\User;
use app\components\ActiveForm;
use yii\web\View;

/**
 * @var View $this
 * @var User $model
 */

/* Если форму запрашивает ajax, имена элементов формы не будут содержать имени модели. */
$model->scopeFormName = !\yii::$app->request->isAjax;
?>

<?php $form = ActiveForm::begin(
    [
        'id' => 'form-user',
        'enableClientScript' => true,
    ]
);
$form->registerClientScript();
?>

<?= $form->field($model, 'second_name') ?>
<?= $form->field($model, 'first_name') ?>
<?= $form->field($model, 'sur_name') ?>

<?php ActiveForm::end(); ?>
