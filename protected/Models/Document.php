<?php

namespace App\Models;

use T4\Orm\Model;


class Document
    extends Model
{
    protected static $schema = [
        'table' => 'documents',
        'columns' => [
            'guid'     => ['type'=>'string'],
            'payload' => ['type' => 'text'],
            'published' => ['type' => 'datetime'],
            'createat'  => ['type' => 'integer'],
            'modifyat'  => ['type' => 'integer'],
            //'__user_id' => ['type' =>'relation']

        ],
        'relations' => [
            'users' => ['type' => self::BELONGS_TO, 'model' => User::class],
        ]
    ];


    static public  function getGUID(){

        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = //chr(123)// "{"
            substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        //.chr(125);// "}"
        return $uuid;

    }
}