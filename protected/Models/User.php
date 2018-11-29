<?php

namespace App\Models;

use T4\Orm\Model;


class User
    extends Model
{
    protected static $schema = [
        'table' => 'users',
        'columns' => [
            'name'     => ['type'=>'string'],
            '__document_id' => ['type' => 'relation']
        ],
        'relations' => [
            'documents' => ['type' => self::HAS_MANY, 'model' => Document::class],
        ]
    ];



}