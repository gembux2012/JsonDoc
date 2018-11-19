<?php

namespace App\Models;

use T4\Orm\Model;


class UserSession
    extends Model
{
    protected static $schema = [
        'table' => 'sessions',
        'columns' => [
            'hash' => ['type' => 'string'],
            'userAgentHash' => ['type' => 'string'],

        ],
        'relations' => [
            'user' => ['type' => self::BELONGS_TO, 'model' => User::class],
        ],
    ];

}