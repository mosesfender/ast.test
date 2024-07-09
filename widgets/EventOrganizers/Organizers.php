<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */


namespace app\widgets\EventOrganizers;

use app\models\Event;
use app\models\User;
use yii\base\Widget;
use yii\base\InvalidConfigException;

class Organizers extends Widget
{
    public ?Event $event = null;
    public array $model = [];
    
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->model = User::find()
            ->select('*')
            ->nameSpelling()
            ->event($this->event->id)
            ->role(User::ROLE_EVENT_ORGANIZER)
            ->orderBy('fullname ASC')
            ->all();
    }
    
    public function run()
    {
        echo $this->render('index', ['model' => $this->model]);
    }
}