<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\modules\rest\controllers;

use app\models\Event;

class EventController extends BaseRestController
{
    public $modelClass = Event::class;
    
    public function actions()
    {
        $result = parent::actions();
        //unset($result['create']);
        return $result;
    }
    
    
    public function actionCreate()
    {
        $model = new Event();
        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
            $model->save();
        }
        return $model;
    }
}
