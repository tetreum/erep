<?php

namespace App\Controllers;

use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Country;
use App\Models\UserGym;
use App\Models\UserItem;
use App\Models\Money;
use App\System\App;
use App\System\AppException;
use App\System\Controller;
use \App\Models\User as UserModel;

class User extends Controller
{
    public function showGyms ()
    {
        $gyms = UserGym::where([
            "uid" => App::user()->getUid()
        ])->first()->toArray();

        $gymList = UserGym::$data;

        foreach ($gymList as $k => &$gym)
        {
            $gym["quality"] = (int)str_replace("q", "", $k);
            $gym["hasTrainedToday"] = false;
            $lastTime = date("Y-m-d", strtotime($gyms[$k]));

            if ($lastTime == date("Y-m-d")) {
                $gym["hasTrainedToday"] = true;
            }
        }

        return $this->render('user/gyms.html.twig', [
            "gyms" => $gymList,
        ]);
    }

    public function train ()
    {
        $uid = App::user()->getUid();
        $quality = (int)$_POST["quality"];

        if ($quality < 1 || $quality > 4) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $gyms = UserGym::where([
            "uid" => $uid
        ])->first();

        if ($gyms->hasTrainedToday($quality)) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $gymDetails = UserGym::$data["q$quality"];

        $userMoney = App::user()->getMoney();

        if ($userMoney->gold < $gymDetails["cost"]) {
            throw new AppException(AppException::NO_ENOUGH_MONEY);
        }

        $gyms["q$quality"] = date("Y-m-d");
        $gyms->save();

        $user = UserModel::where([
            "id" => $uid
        ])->first();

        $user->strength += $gymDetails["strength"];
        return $user->save();
    }

    public function showStorage ()
    {
        $items = UserItem::where([
            "uid" => App::user()->getUid()
        ])->get()->toArray();

        return $this->render('user/storage.html.twig', [
            "items" => $items,
        ]);
    }

    public function work ()
    {
        $list = $_POST["list"];
        $uid = App::user()->getUid();
        $itemModel = new UserItem();

        foreach ($list as &$company) {
            $company = (int)$company;
        }

        $companies = Company::where('uid', $uid)->whereIn("id", $list)->get();
        $production = [];

        foreach ($companies as $company)
        {
            if ($company->hasManagerWorkedToday()) {
                throw new AppException(AppException::ACTION_FAILED);
            }

            // raw companies dont consume any resource
            if (!empty(CompanyType::$types[$company["type"]]["qualities"][$company["quality"]]["consume_product"])) {
                $production[CompanyType::$types[$company["type"]]["qualities"][$company["quality"]]["consume_product"]][0] -= CompanyType::$types[$company["type"]]["qualities"][$company["quality"]]["consume_amount"];
            }

            // set raw products quality to 0 (none) as they dont have qualities
            if ($itemModel->isRaw(CompanyType::$types[$company["type"]]["product"])) {
                $quality = 0;
            } else {
                $quality = $company["quality"];
            }
            $production[CompanyType::$types[$company["type"]]["product"]][$quality] += CompanyType::$types[$company["type"]]["qualities"][$company["quality"]]["product_amount"];
        }

        // check if user misses some resource
        foreach ($production as $product => $qualities)
        {
            foreach ($qualities as $quality => $quantity)
            {
                if ($quantity < 0)
                {
                    // check if user has more in his inventory
                    $item = UserItem::where([
                        "uid" => $uid,
                        "item" => $product,
                        "quality" => $quality,
                    ])->first();
                    $quantity += $item->quantity;

                    if ($quantity < 0) {
                        throw new AppException(AppException::NO_ENOUGH_RESOURCES);
                    }
                }
            }
        }

        // create/update item quantities
        foreach ($production as $product => $qualities)
        {
            foreach ($qualities as $quality => $quantity)
            {
                $item = UserItem::firstOrNew([
                    "uid" => $uid,
                    "item" => $product,
                    "quality" => $quality,
                ]);
                $item->quantity += $quantity;
                $item->save();
            }
        }

        // set that he has worked as manager today
        foreach ($companies as $company)
        {
            $company->last_work = date("Y-m-d H:i:s");
            $company->save();
        }

        return $production;
    }

    public function showSignup ()
    {
        $this->redirectLoggedUsers();

        $referrer = (int)$_GET["referrer"];

        return $this->render('user/signup.html.twig', [
            "referrer" => $referrer,
            "countries" => Country::get()
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
            $country = Country::find($data["country"]);
            $data["region"] = $country["capital"];
            unset($data["country"]);

            $user = UserModel::create($data);

            // create user's entry in money db
            Money::create([
                "uid" => $user["id"]
            ]);
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

        return $this->render('user/login.html.twig');
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

        App::session()->fillUserData($user->toArray());

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