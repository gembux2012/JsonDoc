<?php

namespace App\Components\Auth;

use App\Models\User;
use App\Models\UserSession;
use T4\Mvc\Application;
use T4\Auth\Exception;
use T4\Core\Session;


class Identity
    extends \T4\Auth\Identity
{

    const  ERROR_INVALID_NAME = 105;
    const AUTH_COOKIE_NAME = 'T4auth';

    public function authenticate($name)
    {
        $errors = new MultiException();


        if (empty($name)) {
            throw new Exception('Имя пользователя ?',  self::ERROR_INVALID_NAME);
        }


        $user = User::findByName($name);
        if (empty($user)) {
            throw new Exception('Нет такого пользовател ' . $name , self::ERROR_INVALID_NAME);
        }


        $this->login($user);
        Application::instance()->user = $user;
         return $user;
    }

    public function getUser()
    {
        if (!\T4\Http\Helpers::issetCookie(self::AUTH_COOKIE_NAME))
            return null;

        $hash = \T4\Http\Helpers::getCookie(self::AUTH_COOKIE_NAME);
        $session = UserSession::findByHash($hash);
        if (empty($session)) {
            \T4\Http\Helpers::unsetCookie(self::AUTH_COOKIE_NAME);
            return null;
        }

        if ($session->userAgentHash != md5($_SERVER['HTTP_USER_AGENT'])) {
            $session->delete();
            \T4\Http\Helpers::unsetCookie(self::AUTH_COOKIE_NAME);
            return null;
        }

        return $session->user;
    }




    /**
     * @param \App\Models\User $user
     */
    public function login($user)
    {
        $app = Application::Instance();
        $expire = isset($app->config->auth) && isset($app->config->auth->expire) ?
            time() + $app->config->auth->expire :
            0;
        $hash = md5(time() . rand(5,5));

        \T4\Http\Helpers::setCookie(self::AUTH_COOKIE_NAME, $hash, $expire);

        $session = new UserSession();
        $session->hash = $hash;
        $session->userAgentHash = md5($_SERVER['HTTP_USER_AGENT']);
        $session->user = $user;
        $session->save();
    }

    public function logout()
    {
        if (!\T4\Http\Helpers::issetCookie(self::AUTH_COOKIE_NAME))
            return;

        $hash = \T4\Http\Helpers::getCookie(self::AUTH_COOKIE_NAME);
        $session = UserSession::findByHash($hash);
        if (empty($session)) {
            \T4\Http\Helpers::unsetCookie(self::AUTH_COOKIE_NAME);
            return;
        }

        $session->delete();
        \T4\Http\Helpers::unsetCookie(self::AUTH_COOKIE_NAME);

        $app = Application::Instance();
        $app->user = null;
    }

}