<?php

return [
    'db' => [
        'default' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'dbname' => 'yii2-starter-kit',
            'user' => 'ysk_dbu',
            'password' => 'ysk_pass',
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
    ],

    'errors' => [
    403 => '///403',
    401 => '///401'
    ]
];