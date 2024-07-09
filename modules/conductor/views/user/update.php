<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\models\User;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var User $model */

$this->title = \yii::t('app', 'Update user');
$this->params['breadcrumbs'][] = ['label' => \yii::t('app','Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->shortname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \yii::t('yii', 'Update');
?>
<div class="user-update">
    
    <h1 class="bt"><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
