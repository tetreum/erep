<?php

namespace App\Controllers;

use App\System\App;
use App\System\AppException;
use App\System\Controller;
use \App\Models\User as UserModel;

class User extends Controller
{
    private function redirectLoggedUsers ()
    {
        if ($this->isLogged) {
            App::redirect($this->app->getContainer()->get('router')->pathFor('home'));
            exit;
        }
    }

    public function showSignup ()
    {
        $this->redirectLoggedUsers();

        $referrer = (int)$_GET["referrer"];

        return $this->render('signup.html.twig', [
            "referred" => $referrer
        ]);
    }

    /**
     * Do user signup
     * @return \stdClass
     * @throws AppException
     */
    public function doSignup ()
    {
        $email = strip_tags($_POST["email"]);
        $password = $_POST["password"];
        $referred = (int)$_POST["referred"];

        if (empty($email) || empty($password)) {
            throw new AppException(AppException::INVALID_DATA);
        }
    }

    /**
     * Show login page
     * @return mixed
     */
    public function showLogin ()
    {
        $this->redirectLoggedUsers();

        return $this->render('login.html.twig');
    }

    /**
     * Do user login
     * @return \stdClass
     * @throws AppException
     */
    public function doLogin ()
    {
        $email = strip_tags($_POST["email"]);
        $password = $_POST["password"];

        if (empty($email) || empty($password)) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $user = UserModel::where([
            "email" => $email,
            "password" => md5($this->app->getContainer()->get("settings")["password_hash"] . $password),
            "status" => UserModel::STATUS_ACTIVATED,
        ])->first();

        if (empty($user)) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $this->app->getContainer()->get("session")->fillUserData($user);

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