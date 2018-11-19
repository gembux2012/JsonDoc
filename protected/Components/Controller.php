<?php

namespace App\Components;

class Controller
    extends \T4\Mvc\Controller
{
    protected function afterAction($action)
    {
        $this->data->user=$this->app->user;
        return true;
    }


}