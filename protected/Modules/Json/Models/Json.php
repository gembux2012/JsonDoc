<?php
/**
 * Created by PhpStorm.
 * User: alexc
 * Date: 16.10.18
 * Time: 11:26
 */

namespace App\Modules\Json\Models;
use T4\Fs\Helpers;

class Json
{
    static protected $shema = ['document' => [
        'id' => '',
        'status' => 'draft',
        'payload' => [

        ],
        'createAt' => '',
        'modifyAt' => ''
    ]];

    static private function NewDoc(){
        self::$shema['id']=self::getGUID();
        self::$shema['createAt']=date("Y-m-d H:i:s");

        return self::$shema;
    }

    static public function SaveFile($data){

        $path=Helpers::getRealPath('/jsondoc/1.json');
        try {
            file_put_contents($path, $data);
        } catch(Exception $e){
            $e;
            echo '';
        }
        echo '';
    }

    static public function getContent($source){

        if ($source=='new')
            return self::NewDoc();

    }

    static public function setContent(){

    }

    static private  function getGUID(){

            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
            return $uuid;

    }



}