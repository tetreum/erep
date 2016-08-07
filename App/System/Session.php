<?php

namespace App\System;

class Session
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;

        ini_set("session.gc_maxlifetime", "18000");
        session_cache_limiter(false);

        session_set_cookie_params(18000, '/', $this->app->getContainer()->get('settings')['cookies.domain']);
        session_start();
    }

    /**
     * Fills user data in current session
     * @param string $user
     * @param string $password
     */
    public function fillUserData ($user, $password = null)
    {
        $_SESSION['user'] = [
            "name" => $user,
        ];

        if (!empty($password)) {
            $_SESSION['user']["password"] = $password;
        }
    }

    /**
     * Logs the user out
     */
    public function logout()
    {
        session_destroy();

        $this->app->isAjax = false;
        $this->isLogged = false;

        $previousPath = "";
        if (isset($_REQUEST["redirect"]) && !empty($_REQUEST["redirect"])) {
            $previousPath  = "?redirect=" . urlencode($_REQUEST["redirect"]);
        }

        App::redirect($this->app->getContainer()->get('router')->pathFor('login') . $previousPath);
    }

    /**
     * Returns if user is logged
     * @return bool
     */
    public function isLogged()
    {
        return !empty($_SESSION['user']);
    }

    /**
     * Route middleware to guarantee that user is logged before visiting the path
     */
    public function ensureLogged()
    {
        if (!$this->isLogged())
        {
            if ($this->app->isAjax) {
                header("Content-Type: application/json");
                echo json_encode(['error' => 11]);
                exit;
            } else {
                $router = $this->app->getContainer()->get("router");
                $params = "";

                if (!empty($_GET)) {
                    $params = "?" . http_build_query($_GET);
                }
                App::redirect($router->pathFor('login') . $params);
            }
        }
    }

    /**
     * Gets current user data
     * @return null
     */
    public function getUser()
    {
        if (!$this->isLogged()) {
            return null;
        }

        return $_SESSION['user'];
    }

    /**
     * Sets a session var
     * @param string $k
     * @param mixed $v
     */
    public function set($k, $v)
    {
        $_SESSION[$k] = $v;
    }

    /**
     * Gets a session var
     * @param string $k
     * @return mixed
     */
    public function get($k)
    {
        return $_SESSION[$k];
    }

    /**
     * Deletes a session var
     * @param string $k
     */
    public function del($k)
    {
        unset($_SESSION[$k]);
    }
}
