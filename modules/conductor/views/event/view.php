<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\models\Event;
use app\models\EventSearch;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Event $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="event-view">
    
    <h1 class="bt"><?= Html::encode($this->title) ?></h1>
    
    <?php if (\yii::$app->user->can('event.update', $model)) { ?>
        <p>
            <?= Html::a(Yii::t('app', 'Write'), ['update', 'id' => $model->id],
                        ['class' => 'btn btn-sm btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-sm btn-danger',
                'data'  => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method'  => 'post',
                ],
            ]) ?>
        </p>
    <?php } ?>
    
    <?= DetailView::widget([
                               'model'      => $model,
                               'attributes' => [
                                   'id',
                                   'title:ntext',
                                   'description:ntext',
                                   'begin_time',
                                   [
                                       'label' => \yii::t('app', 'Created by'),
                                       'format' => 'raw',
                                       'value'         => function (Event $data) {
                                            return $data->creator->fullname;
                                       }
                                   ],
                                   [
                                       'label' => \yii::t('app', 'Organizers'),
                                       'format' => 'raw',
                                       'value'         => function (Event $data) {
                                           $result = [];
                                           foreach ($data->users as $user) {
                                               $result[] = $user->fullname;
                                           }
                                           return implode('<br/>', $result);
                                       }
                                   ],
                                   'flags' => [
                                       'label' => \yii::t('app', 'Flags'),
                                       'value' => function (Event $model) {
                                           return Event::flagList($model->flags);
                                       },
                                   ],
                                   'created_at',
                                   'updated_at',
                               ],
                           ]) ?>

</div>
