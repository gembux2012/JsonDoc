<?php

namespace App\Components\Auth;

use App\Models\User;
use App\Models\UserSession;
use T4\Mvc\Application;
use T4\Core\Session;
use T4\Core\MultiException;

class Identity
    extends \T4\Auth\Identity
{

    const  ERROR_INVALID_NAME = 105;

    public function authenticate($data)
    {
        $errors = new MultiException();

        if (empty($data->name)) {
            $errors->add('Не введен e-mail', self::ERROR_INVALID_EMAIL);
        }

        $user = User::findByEmail($data->email);
        if (empty($user)) {
            $errors->add('Нет такого ользовател  ' . $data->email . ' не существует', self::ERROR_INVALID_EMAIL);
        }

        if (!$errors->isEmpty())
            throw $errors;

        $this->login($user);
        Application::getInstance()->user = $user;
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
        $app = Application::getInstance();
        $expire = isset($app->config->auth) && isset($app->config->auth->expire) ?
            time() + $app->config->auth->expire :
            0;
        $hash = md5(time() . $user->password);

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

        $app = Application::getInstance();
        $app->user = null;
    }

}