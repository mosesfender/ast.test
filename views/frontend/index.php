<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\components\SVSGridView;
use app\models\EventSearch;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\ListView;

/**
 * @var View               $this
 * @var ActiveDataProvider $dataProvider
 * @var EventSearch        $searchModel
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
?>

<div class="event-front">
    
    <?= $this->render('_search', ['model' => $searchModel]) ?>
    
    <?php $list = SVSGridView::begin(
        [
            'id'           => 'simple-events',
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table without-header'],
            'columns'      => [
                [
                    'format' => 'raw',
                    'value'  => function (EventSearch $data) use ($hd) {
                        return <<<HTML
                            
                            <dl>
                                <dt>{$hd(\yii::t('app', 'Begin time'))}</dt>
                                <dd>{$hd(\yii::$app->formatter->asDatetime($data->begin_time, 'full'))}</dd>
                            </dl>
                            <h4>{$data->title}</h4>
                            <div class="description">
                                {$this->render('_organizers', ['list' => $data->users])}
                                {$hd(nl2br($data->description))}
                            </div>
                        HTML;
                    }
                ]
            ]
        ]
    ) ?>
    
    <?php SVSGridView::end(); ?>
</div>