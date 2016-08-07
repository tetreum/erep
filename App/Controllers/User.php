<?php

namespace App\Controllers;

use App\System\App;
use App\System\AppException;
use App\System\Controller;
use \App\Models\User as UserModel;

class User extends Controller
{
    public function showSignup ()
    {
        $this->redirectLoggedUsers();

        $referrer = (int)$_GET["referrer"];

        return $this->render('signup.html.twig', [
            "referrer" => $referrer
        ]);
    }

    /**
     * Do user signup
     * @return \stdClass
     * @throws AppException
     */
    public function signup ()
    {
        $data = [
            "email" => strip_tags($_POST["email"]),
            "nick" => strip_tags($_POST["username"]),
            "password" => strip_tags($_POST["password"]),
            "referrer" => (int)$_POST["referrer"],
            "country" => (int)$_POST["country"]
        ];

        foreach ($data as $field => $val) {
            if (empty($val) && $field != "referrer") {
                throw new AppException(AppException::INVALID_DATA);
            }
        }

        if (strlen($data["password"]) < UserModel::MIN_PASSWORD_LENGTH || !self::isValidEmail($data["email"])) {
            throw new AppException(AppException::INVALID_DATA);
        }

        if (empty($data["referrer"])) {
            unset($data["referrer"]);
        }

        $data["password"] = self::encryptPassword($data["password"]);

        // @Todo email verification
        $data["status"] = UserModel::STATUS_ACTIVATED;

        try {
            UserModel::create($data);
        } catch (\Exception $e) {
            throw new AppException(AppException::ACTION_FAILED);
        }

        return true;
    }

    private static function encryptPassword ($password) {
        return md5(App::container()->get("settings")["password_hash"] . $password);
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
            "password" => self::encryptPassword($password),
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

    private function redirectLoggedUsers ()
    {
        if ($this->isLogged) {
            App::redirect($this->app->getContainer()->get('router')->pathFor('home'));
            exit;
        }
    }
    private static function isValidEmail($email) {
        return !!filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}