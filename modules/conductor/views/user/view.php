<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->shortname;
$this->params['breadcrumbs'][] = ['label' => \yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="user-view">
    <div>
        <h1 class="bt"><?= Html::encode($this->title) ?></h1>
        <p>
            <?= \yii::$app->user->can('user.update')
                ? Html::a(\yii::t('yii', 'Update'), ['update', 'id' => $model->id],
                          ['class' => 'btn btn-sm btn-primary']) : '' ?>
            <?php if (!$model->inTrash && \yii::$app->user->can('user.delete')) {
                echo Html::a(\yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-danger',
                    'data'  => [
                        'confirm' => \yii::t('yii', 'Are you sure you want to delete this item?'),
                        'method'  => 'delete',
                    ],
                ]);
            }
            ?>
        </p>
    </div>
    <?= DetailView::widget(
        [
            'model'      => $model,
            'attributes' => [
                'id',
                'username',
                'email:email',
                'phone:ntext',
                'second_name:ntext',
                'first_name:ntext',
                'sur_name:ntext',
                'shortname:ntext',
                'fullname:ntext',
                'role'  => [
                    'label' => \yii::t('app', 'Role'),
                    'value' => function (User $model) {
                        return User::roleList($model->role);
                    },
                ],
                'flags' => [
                    'label' => \yii::t('app', 'Flags'),
                    'value' => function (User $model) {
                        return User::flagList($model->flags);
                    },
                ],
                'created_at:datetime:short',
                'updated_at:datetime:short',
            ],
        ]
    ) ?>

</div>
