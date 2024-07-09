<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\models\EventSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use app\components\SVSGridView as GridView;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View               $this
 * @var EventSearch        $searchModel
 * @var ActiveDataProvider $dataProvider
 */

/**
 * For heredoc
 *
 * @param $str
 *
 * @return mixed
 */
$hd = function ($str) {
    return $str;
};

$this->title = Yii::t('app', 'Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">
    
    <div>
        <h1 class="bt"><?= Html::encode($this->title) ?></h1>
        <?php if (\yii::$app->user->can('event-man')) { ?>
            <?= Html::a(Yii::t('app', 'Create Event'), ['create'],
                        ['class' => 'btn btn-sm btn-primary']) ?>
        <?php } ?>
    </div>
    
    <?php Pjax::begin(['id' => 'j_events']); ?>
    
    <div class="accordion mb-3">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#event-filter" aria-expanded="false"
                        aria-controls="panelsStayOpen-collapseOne"><?= \yii::t('app', 'Filter') ?>
                </button>
            </h2>
            <div id="event-filter" class="accordion-collapse collapse">
                <div class="accordion-body">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col mt-4">
        <?= GridView::widget(
            [
                'id'           => 'event_grid',
                'tableOptions' => ['class' => 'table table-bordered'],
                'dataProvider' => $dataProvider,
                'columns'      => [
                    [
                        'format' => 'raw',
                        'header' => \yii::t('app', 'General'),
                        'value'  => function (EventSearch $data) use ($hd) {
                            return <<<HTML
                            <div class="event-wrap">
                                <div class="event-title">{$data->title}</div>
                                <div class="event-description" is="svs-spoiler">{$hd(nl2br($data->description))}</div>
                                <div>
                                    <dd>{$hd(\yii::t('app', 'ID'))}:</dd>
                                    <dt class="num">{$data->id}</dt>
                                    <dd>{$hd(\yii::t('app', 'Registered at'))}:</dd>
                                    <dt>{$hd(\yii::$app->formatter->asDatetime($data->created_at, 'medium'))}</dt>
                                </div>
                                <div>
                                    <dd>   </dd>
                                    <dt class="num"> </dt>
                                    <dd>{$hd(\yii::t('app', 'Created by'))}:</dd>
                                    <dt>{$data->creator->shortname}</dt>
                                </div>
                            </div>
                            HTML;
                        }
                    ],
                    [
                        'attribute'      => 'begin_time',
                        'format'         => 'raw',
                        'header'         => \yii::t('app', 'Begin Time'),
                        'headerOptions'  => ['style' => 'width: 6rem'],
                        'contentOptions' => ['class' => 'text-end'],
                        'value'          => function (EventSearch $data) {
                            return \yii::$app->formatter->asDatetime((new DateTime($data->begin_time)), 'short');
                            //return date('d.m.Y H:i:s', (new DateTime($data->begin_time))->getTimestamp());
                        }
                    ],
                    [
                        'format'        => 'raw',
                        'header'        => \yii::t('app', 'Organizers'),
                        'headerOptions' => ['style' => 'width: 18rem'],
                        'value'         => function (EventSearch $data) {
                            $result = [];
                            foreach ($data->users as $user) {
                                $result[] = $user->fullname;
                            }
                            return implode('<br/>', $result);
                        }
                    ],
                    [
                        'headerOptions'  => ['style' => 'width: 100px'],
                        'contentOptions' => ['class' => 'actions'],
                        'format'         => 'raw',
                        'value'          => function (EventSearch $model) {
                            $result = Html::a('', ['/conductor/event/view', 'id' => $model->id],
                                              [
                                                  'class'       => 'si si-read',
                                                  'data-toggle' => 'tooltip',
                                                  'title'       => \yii::t('app', 'Read')]);
                            if (\yii::$app->user->can('event.update', $model)) {
                                $result .= Html::a('', ['/conductor/event/update', 'id' => $model->id],
                                                   [
                                                       'class'       => 'si si-write',
                                                       'data-toggle' => 'tooltip',
                                                       'title'       => \yii::t('app', 'Write')])
                                    . Html::a('', ['/conductor/event/delete', 'id' => $model->id],
                                              [
                                                  'class'        => 'si si-delete',
                                                  'data-toggle'  => 'tooltip',
                                                  'title'        => \yii::t('app', 'Delete'),
                                                  'data-confirm' => \yii::t('yii',
                                                                            'Are you sure you want to delete this item?'),
                                                  'data-method'  => 'delete',
                                              ]);
                            }
                            return $result;
                        }
                    ]
                ],
            ]
        ); ?>
        
        <?php Pjax::end(); ?>
    </div>
</div>
