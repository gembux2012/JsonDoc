<?php

namespace App\Modules\Json\Models;

use T4\Orm\Model;


class User
    extends Model
{
    protected static $schema = [
        'table' => 'users',
        'columns' => [
            'name'     => ['type'=>'string'],

        ],
        'relations' => [
            'documents' => ['type' => self::HAS_MANY, 'model' => Document::class],
        ]
    ];



}