<?php

namespace App\Components;

class Controller
    extends \T4\Mvc\Controller
{
    protected static $user=['user' => '', 'err' => ''];

    protected function afterAction($action)
    {
        self::$user['user']=$this->app->user;
        $this->data->user=self::$user;
        return true;
    }


}