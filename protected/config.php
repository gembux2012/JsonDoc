<?php

return [
    'db' => [
        'default' => [
            'driver' => 'mysql',
            'host' => 'db',
            'dbname' => 'jsondoc',
            'user' => 'jsondoc',
            'password' => 'root',
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