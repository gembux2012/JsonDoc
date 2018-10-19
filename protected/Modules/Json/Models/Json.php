<?php
/**
 * Created by PhpStorm.
 * User: alexc
 * Date: 16.10.18
 * Time: 11:26
 */

namespace App\Modules\Json\Models;

class Json
{
    static protected $shema = [
        'id' => '0',
        'status' => '0',
        'payload' => [],
        'createAt' => '0',
        'modifyAt' => '0'
    ];

    static public function NewDoc(){
        self::$shema['id']=0;
        //var_dump(date());die;
        self::$shema['createAt']=2;

        return static::$shema;
    }

    static public function SaveFile($data){
        file_put_contents(ROOT_PATH_PUBLIC.DC.'jsondoc'.DC.com_create_guid().'.json',$data);

    }

}