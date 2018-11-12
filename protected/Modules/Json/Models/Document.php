<?php

namespace App\Modules\Json\Models;

use T4\Orm\Model;


class Document
    extends Model
{
    protected static $schema = [
        'table' => 'documents',
        'columns' => [
            'guid'     => ['type'=>'string'],
            'payload' => ['type' => 'json'],
            '__user_id' => ['type' =>'relation']

        ],
        'relations' => [
            'users' => ['type' => self::HAS_ONE, 'model' => User::class],
        ]
    ];



}