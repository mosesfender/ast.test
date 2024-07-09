<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\components\UserActionColumn;
use app\models\User;
use app\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\SVSGridView as GridView;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View               $this
 * @var UserSearch         $searchModel
 * @var ActiveDataProvider $dataProvider
 */

$this->title = \yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;

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
<div class="user-index">
    <div>
        <h1 class="bt"><?= Html::encode($this->title) ?></h1>
        <?= \yii::$app->user->can('user.create')
            ? Html::a(\yii::t('app', 'Create User'), ['create'],
                      ['class' => 'btn btn-sm btn-primary']) : ''
        ?>
    </div>
    
    <?php Pjax::begin(); ?>
    
    <div class="accordion mb-3" id="accordionPanelsStayOpenExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#user-filter" aria-expanded="false"
                        aria-controls="panelsStayOpen-collapseOne"><?= \yii::t('app', 'Filter') ?>
                </button>
            </h2>
            <div id="user-filter" class="accordion-collapse collapse">
                <div class="accordion-body">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php try {
        echo GridView::widget(
            [
                'id'           => 'user_grid',
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-bordered table-hover without-header'],
                'rowOptions'   => function (User $model) {
                    $classes = [];
                    $model->enabled ? null : $classes[] = 'disabled';
                    $model->inTrash ? $classes[] = 'trash' : null;
                    return ['class' => implode(' ', $classes)];
                },
                'columns'      => [
                    [
                        'format' => 'raw',
                        'value'  => function (UserSearch $data) use ($hd) {
                            return <<<HTML
                                <div class="fullname">{$data->fullname}</div>
                                <div>
                                    <dd>{$hd(\yii::t('app', 'ID'))}:</dd>
                                    <dt class="num">{$data->id}</dt>
                                    <dd>{$hd(\yii::t('app', 'Registered at'))}:</dd>
                                    <dt>{$hd(\yii::$app->formatter->asDatetime($data->created_at, 'medium'))}</dt>
                                </div>
                                <div>
                                    <dd>{$hd(\yii::t('app', 'Username'))}:</dd>
                                    <dt>{$data->username}</dt>
                                    <dd>{$hd(\yii::t('app', 'Email'))}:</dd>
                                    <dt><a href="mailto:{$data->email}">{$data->email}</a></dt>
                                    <dd>{$hd(\yii::t('app', 'Phone'))}:</dd>
                                    <dt><a href="tel:{$data->phone}">{$data->phone}</a></dt>
                                </div>
                            HTML;
                        }
                    ],
                    [
                        'headerOptions'  => ['style' => 'width: 150px'],
                        'contentOptions' => ['class' => 'actions'],
                        'format'         => 'raw',
                        'value'          => function (User $model) {
                            $result = Html::a('', ['/conductor/user/view', 'id' => $model->id],
                                              [
                                                  'class'       => 'si si-read',
                                                  'data-toggle' => 'tooltip',
                                                  'title'       => \yii::t('app', 'Read')])
                                . Html::a('', ['/conductor/user/update', 'id' => $model->id],
                                          [
                                              'class'       => 'si si-write',
                                              'data-toggle' => 'tooltip',
                                              'title'       => \yii::t('app', 'Write')]);
                            if ($model->enabled && !$model->inTrash) {
                                $result .= Html::a('', ['/conductor/user/delete', 'id' => $model->id],
                                                   [
                                                       'class'        => 'si si-delete',
                                                       'data-toggle'  => 'tooltip',
                                                       'title'        => \yii::t('app', 'Delete'),
                                                       'data-confirm' => \yii::t('yii',
                                                                                 'Are you sure you want to delete this item?'),
                                                       'data-method'  => 'delete',
                                                   ])
                                    . Html::a('', ['/conductor/user/change-password', 'id' => $model->id],
                                              [
                                                  'class'       => 'si si-password',
                                                  'data-toggle' => 'tooltip',
                                                  'title'       => \yii::t('app', 'Change password')
                                              ]);
                                
                            }
                            return $result;
                        }
                    ]
                ],
            ]
        );
    } catch (Throwable $e) {
    } ?>
    
    <?php Pjax::end(); ?>
</div>
