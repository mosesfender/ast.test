<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\components\rbac;

use app\models\EventSearch;
use yii\rbac\Item;
use yii\rbac\Rule;

class EventAuthorRule extends Rule
{
    
    public $name = 'ruleEventAuthor';
    
    /**
     * @param int|string  $user
     * @param Item        $item
     * @param EventSearch $params
     *
     * @return bool|void
     */
    public function execute($user, $item, $params)
    {
        /* @var $params EventSearch */
        return $params->isNewRecord || $params->created_by == $user;
    }
}

