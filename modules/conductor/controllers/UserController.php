<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\modules\conductor\controllers;

use app\components\ModelException;
use app\models\User;
use app\models\UserSearch;
use app\modules\conductor\controllers\BaseWebController;
use yii\base\InvalidConfigException;
use yii\base\UserException;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * Методы пользователей сайта для администраторов приложения
 */
class UserController extends BaseWebController
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
     * Список пользователей
     *
     * @return string
     * @throws InvalidConfigException
     */
    public function actionIndex($trash = false): string
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(ArrayHelper::merge($this->request->queryParams, ['trash' => $trash]));
        
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * @throws InvalidConfigException
     */
    public function actionTrash(): string
    {
        $index = $this->createAction('index');
        return $index->runWithParams(['trash' => true]);
    }
    
    /**
     * Displays a single User model.
     *
     * @param int $id
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_CREATE;
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $me = new ModelException('', $model->errors);
                \yii::$app->session->addFlash('error', $me->toString());
            }
        } else {
            $model->loadDefaultValues();
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = User::SCENARIO_UPDATE;
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $me = new ModelException('', $model->errors);
                \yii::$app->session->addFlash('error', $me->toString());
            }
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionDelete($id)
    {
        /* @var User $model */
        if (($model = $this->findModel($id)) instanceof User) {
            $model->scenario = User::SCENARIO_UPDATE;
            $model->enabled = 0;
            $model->inTrash = 1;
            if (!$model->save(true)) {
                $me = new ModelException('', $model->errors);
                \yii::$app->session->addFlash('error', $me->toString());
            }
        }
        
        return $this->redirect(['/conductor/user/index']);
    }
    
    public function actionChangePassword()
    {
        throw new UserException('Смена пароля не реализована в данном тестовом задании из-за вероятных затруднений в следствии использования этой возможности.', 335);
    }
    
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int  $id
     * @param bool $inTrash
     *
     * @return User
     * @throws NotFoundHttpException|InvalidConfigException if the model cannot be found
     */
    protected function findModel(int $id, ?bool $inTrash = false): User
    {
        /* @var User $model */
        if (($model = User::find()
                ->nameSpelling()
                ->byID($id)
                //->trash($inTrash)
                ->one()) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
