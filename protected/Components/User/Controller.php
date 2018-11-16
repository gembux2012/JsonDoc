<?php

namespace App\Components\User;

class Controller
    extends \T4\Mvc\Controller
{

    protected function access($action)
    {
        return !empty($this->app->user) ;
    }

} 