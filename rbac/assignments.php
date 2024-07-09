<?php

use app\models\User;

return [
    User::ROLE_SUPER => [
        'super',
    ],
    User::ROLE_EVENT_ORGANIZER => [
        'eo',
    ],
    User::ROLE_SIMPLE_USER => [
        'su',
    ],
];
