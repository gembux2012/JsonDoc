<?php

namespace App\Components;
use T4\Http\E403Exception;

class Controller
    extends \T4\Mvc\Controller
{
    protected static $user=['user' => '', 'err' => ''];

    protected function afterAction($action)
    {
        if($this->app->user)
            self::$user['user'] = $this->app->user->name;
            $this->data->user = self::$user;
        return true;


    }

    protected function access($action){

        if('Edit' == $action)
            if(!$this->app->user)
               return false;
    }


}