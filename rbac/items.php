<?php

return [
    'user.create'  => [
        'type' => 2
    ],
    'user.update'  => [
        'type' => 2
    ],
    'user.view'    => [
        'type' => 2
    ],
    'user.delete'  => [
        'type' => 2
    ],
    'user-man'     => [
        // Действия с пользователями
        'type'     => 2,
        'children' => [
            'user.create',
            'user.update',
            'user.view',
            'user.delete',
        ]
    ],
    'event.list' => [
        'type' => 2,
    ],
    'event.view' => [
        'type' => 2,
    ],
    'event.create' => [
        'type' => 2,
        'ruleName' => 'ruleEventAuthor',
    ],
    'event.update' => [
        'type' => 2,
        'ruleName' => 'ruleEventAuthor',
    ],
    'event.delete' => [
        'type' => 2,
        'ruleName' => 'ruleEventAuthor',
    ],
    'event-man'    => [
        // Действия с мероприятиями create, update, delete
        'type'     => 2,
        'children' => [
            'event.create',
            'event.list',
            'event.view',
            'event.update',
            'event.delete',
        ]
    ],
    'su'           => [
        'type' => 1,
    ],
    'eo'           => [
        'type'     => 1,
        'children' => [
            'su',
            'event-man',
        ],
    ],
    'super'        => [
        'type'     => 1,
        'children' => [
            'user-man',
            'event.view',
            'event.list',
        ],
    ],
];
