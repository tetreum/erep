<?php

namespace App\Controllers;

use App\Models\MilitiaMember;
use App\System\App;
use App\System\AppException;
use App\System\Controller;
use App\System\Input;
use App\Models\Militia as MilitiaModel;

class Militia extends Controller
{
    public function create ()
    {
        $name = Input::getString("name", true);
        $description = Input::getString("description", true);
        $uid = App::user()->getUid();

        if (strlen($name) < 4 || strlen($description) < 10) {
            throw new AppException(AppException::INVALID_DATA);
        }

        // only 1 militia per user is allowed
        $militia = MilitiaModel::where([
            "uid" => $uid
        ]);

        if ($militia) {
            throw new AppException(AppException::INVALID_DATA);
        }

        App::user()->buy(MilitiaModel::CREATION_COST, "gold", "NEWSPAPER");

        $success = MilitiaModel::create([
            "name" => $name,
            "description" => $description,
            "country" => App::user()->getLocation()->country->id,
            "uid" => $uid
        ]);

        if ($success) {

            MilitiaMember::create([
                "militia" => $success->id,
                "uid" => $uid,
                "rank" => MilitiaMember::RANK_OWNER
            ]);

            return $success->id;
        }

        throw new AppException(AppException::ACTION_FAILED);
    }

    public function join ()
    {
        $id = Input::getInteger("id");

        if ($id < 1) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $myMilitia = App::user()->getMilitia();

        if ($myMilitia) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $success = MilitiaMember::create([
            "militia" => $id,
            "uid" => App::user()->getUid()
        ]);

        if ($success) {
            return true;
        }

        throw new AppException(AppException::ACTION_FAILED);
    }

    public function leave ()
    {
        $myMilitia = App::user()->getMilitia();

        if (!$myMilitia || $myMilitia->militia->uid == App::user()->getUid()) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $myMilitia->delete();

        return true;
    }
}