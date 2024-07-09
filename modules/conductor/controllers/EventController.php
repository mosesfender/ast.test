<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\modules\conductor\controllers;

use app\components\ModelException;
use app\models\Event;
use app\models\EventSearch;
use app\models\User;
use app\modules\conductor\controllers\BaseWebController;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends BaseWebController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'delete' => ['DELETE'],
                    ],
                ],
            ]
        );
    }
    
    /**
     * Lists all Event models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single Event model.
     *
     * @param int $id ID
     *
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Event([
                               'created_by' => \yii::$app->user->id
                           ]);
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post(), 'Event') && $model->save()) {
                $this->registerOrganizers($model, $this->request->post('User', []));
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
            
            }
        } else {
            $model->loadDefaultValues();
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id ID
     *
     * @return string|Response
     * @throws NotFoundHttpException|Exception|InvalidConfigException|ForbiddenHttpException if the model cannot be
     *                                                                                       found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if (!\yii::$app->user->can('event.update', $model)) {
            throw new ForbiddenHttpException(\yii::t('app',
                                                     'Editing is available only to the author. You cannot edit this event.'));
        }
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post(), 'Event') && $model->save()) {
                $this->registerOrganizers($model, $this->request->post('User', []));
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     *
     * @return Response
     * @throws NotFoundHttpException|ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if (!\yii::$app->user->can('event.update', $model)) {
            throw new ForbiddenHttpException(\yii::t('app',
                                                     'Deletion is available only to the author. You cannot delete this event.'));
        }
        
        $model->enabled = 0;
        $model->inTrash = 1;
        if (!$model->save(true)) {
            $me = new ModelException('', $model->errors);
            \yii::$app->session->addFlash('error', $me->toString());
        }
        
        return $this->redirect(['/conductor/event/index']);
    }
    
    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id ID
     *
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne(['id' => $id])) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException(\yii::t('app', 'The requested page does not exist.'));
    }
    
    /**
     * Метод устанавливает связи организаторов $users с мероприятием $event.
     * Если среди организаторов в мероприятии есть пользователи, идентификаторы которых отсутствуют среди пришедших
     * из формы, метод удаляет такие связи.
     *
     * @param Event $event
     * @param array $users
     *
     * @throws Exception
     * @throws InvalidConfigException
     * @throws StaleObjectException
     */
    protected function registerOrganizers(Event $event, array $users)
    {
        /* @var User[] $income Массив моделей организаторов мероприятия, пришедших из формы */
        $income = empty($users) ? [] : User::find()->byID($users)->indexBy('id')->all();
        
        foreach ($event->users as $isset) {
            /* Смотрим существующих у мероприятия организаторов, и если $isset нет среди $income, то удаляем его */
            if (!key_exists($isset->id, $income)) {
                $event->unlink('users', $isset, true);
            } else {
                /* Если организатор уже существует, то оставляем его как есть, а из входящего массива его удаляем. */
                unset($income[$isset->id]);
            }
        }
        
        /* Теперь в $income остались только пользователи, которых нужно добавить к организаторам мероприятия */
        foreach ($income as $user) {
            $event->link('users', $user);
        }
    }
}
