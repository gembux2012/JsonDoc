<?php

return [
    'db' => [
        'default' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'dbname' => 'standard',
            'user' => 'root',
            'password' => 'Password00',
        ],
    ],
    'auth' => [
        'expire' => 1500 // 25 min
    ],
    'extensions' => [
        'jquery' => [
            'location' => 'local',
        ],

        'bootstrap' => [
            'location' => 'local',
            'theme' => '',
        ]
    ]
];