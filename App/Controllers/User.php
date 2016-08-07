<?php

namespace App\Controllers;

use App\System\App;
use App\System\AppException;
use App\System\Controller;

class User extends Controller
{
    /**
     * Show login page
     * @return mixed
     */
    public function showLogin ()
    {
        if ($this->isLogged) {
            App::redirect($this->app->getContainer()->get('router')->pathFor('home'));
            exit;
        }

        return $this->render('login.html.twig');
    }

    /**
     * Do user login
     * @return \stdClass
     * @throws AppException
     */
    public function doLogin ()
    {
        $user = strip_tags($_POST["username"]);
        $password = strip_tags($_POST["password"]);

        if (empty($user) || empty($password)) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $settings = $this->app->getContainer()->get("settings");

        if (isset($settings["local_auth"])) {
            $this->app->getContainer()->get("session")->fillUserData($user);
        } else {
            $this->app->getContainer()->get("session")->fillUserData($user, $password);
        }

        $response = new \stdClass();
        $response->error = 0;
        return $response;
    }

    /**
     * Log out user
     */
    public function logout () {
        $this->app->getContainer()->get("session")->logout();
    }
}