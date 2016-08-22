<?php

namespace App\System;

use App\Models\CongressMember;
use App\Models\MilitiaMember;
use App\Models\PartyMember;
use App\Models\UserItem;
use App\Models\UserMoney;
use App\Models\Region;
use App\Models\WorkOffer;

class Session
{
    private $app;

    const PURCHASE_TYPE_COMPANY = "company";

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
     * @param array $user
     */
    public function fillUserData ($user)
    {
        unset($user["password"]);

        $_SESSION['user'] = $user;
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

    public function getUid () {
        return $this->getUser()["id"];
    }

    /**
     * Gets user's money
     * @return UserMoney
     */
    public function getMoney ()
    {
        return UserMoney::where("uid", $this->getUid())->first();
    }

    /*
     * Gets user's location
     * @return array
     */
    public function getLocation ()
    {
        return Region::getFullInfo($this->getUser()["region"]);
    }

    /**
     * @return WorkOffer
     */
    public function getJob ()
    {
        return WorkOffer::where([
            "worker" => App::session()->getUid()
        ])->first();
    }

    /**
     * @return PartyMember
     */
    public function getPoliticalParty ()
    {
        return PartyMember::with("partyData")->where([
            "uid" => App::session()->getUid()
        ])->first();
    }

    /**
     * @return MilitiaMember
     */
    public function getMilitia ()
    {
        return MilitiaMember::with("militiaData")->where([
            "uid" => App::session()->getUid()
        ])->first();
    }

    /**
     * @return array
     */
    public function getItems ()
    {
        return UserItem::where([
            "uid" => App::session()->getUid()
        ])->get();
    }

    /**
     * Checks if user can pay
     * @param $amount
     * @param $currency
     */
    public function buy ($amount, $currency, $purchaseType)
    {
        // get the local currency
        if ($currency == "local") {
            $currency = $this->getLocation()["country"]["currency"];
        }

        $money = $this->getMoney();

        if (empty($money[$currency]) || $money[$currency] < $amount) {
            throw new AppException(AppException::NO_ENOUGH_MONEY);
        }

        $money[$currency] -= $amount;

        if ($money->save()) {
            return true;
        }

        return false;
    }

    public function isCongressist ()
    {
        return (CongressMember::where([
            "uid" => $this->getUid()
        ])->first() == true);
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
