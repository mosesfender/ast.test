<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\modules\conductor\controllers;

use yii\filters\AccessControl;
use yii\validators\ValidationAsset;
use yii\web\Controller;
use yii\widgets\ActiveFormAsset;

class BaseWebController extends Controller
{
    public function behaviors()
    {
        return [
            /* Настройки доступа ко всем действиям всех контроллеров. Их тут немного, поэтому можно и так. */
            /* Разрешения|роли в файлах @app/rbac под управлением components/RbacPhpManager.php */
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'       => true,
                        'controllers' => ['conductor/event'],
                        'actions'     => ['create', 'update', 'delete'],
                        'roles'       => ['event-man']
                    ],
                    [
                        'allow'       => true,
                        'controllers' => ['conductor/event'],
                        'actions'     => ['index', 'view'],
                        'roles'       => ['eo']
                    ],
                    [
                        'allow'       => true,
                        'controllers' => ['conductor/user'],
                        'actions'     => ['index', 'view', 'create'],
                        'roles'       => ['user-man']
                    ],
                    [
                        'allow'       => true,
                        'controllers' => ['conductor/user'],
                        'actions'     => ['update', 'delete'],
                        'roles'       => ['event.update', 'event.delete']
                    ],
                    [
                        'allow'       => true,
                        'controllers' => ['conductor/user'],
                        'actions'     => ['change-password'],
                        'roles'       => ['@']
                    ],
                    [
                        'allow'       => true,
                        'controllers' => ['conductor/backend'],
                        'actions'     => ['index'],
                        'roles'       => ['eo']
                    ],
                ]
            ]
        ];
    }
}