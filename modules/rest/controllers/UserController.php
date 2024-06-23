<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\modules\rest\controllers;

use app\models\User;
use JetBrains\PhpStorm\ArrayShape;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\View;

class UserController extends BaseRestController
{
    public $modelClass = User::class;
    
    public function actions()
    {
        $result = parent::actions();
        //unset($result['create']);
        return $result;
    }
    
    protected function verbs()
    {
        return ArrayHelper::merge(parent::verbs(), [
            'form' => ['GET'],
        ]);
    }
    
    /**
     * @method POST
     * Создание записи пользователя
     *
     * @throws Exception
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     */
    public function actionCreate()
    {
        /* @var $model User */
        $model = new $this->modelClass(
            [
                'scenario' => Model::SCENARIO_DEFAULT,
            ]
        );
        
        $model->load(\yii::$app->getRequest()->getBodyParams(), 'User');
        if ($model->save()) {
            $response = \yii::$app->getResponse();
            $response->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        
        return $model;
    }
    
    //#[ArrayShape(['html' => "string"])]
    public function actionForm(int $id = null)
    {
        $model = $id ? User::findOne($id) : new User();
        return $this->renderActiveFormData('@app/views/user/form', compact('model'));
    }
}

