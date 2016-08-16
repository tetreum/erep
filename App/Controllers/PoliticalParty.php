<?php

namespace App\Controllers;

use App\Models\Money;
use App\Models\PartyMember;
use App\System\App;
use App\System\AppException;
use App\System\Controller;
use \App\Models\PoliticalParty as PartyModel;
use App\System\Utils;

class PoliticalParty extends Controller
{
    public function join ()
    {
        $id = (int)$_POST["id"];

        if ($id < 1) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $created = PartyMember::create([
            "party" => $id,
            "uid" => App::user()->getUid(),
            "level" => PartyMember::LEVEL_AFFILIATED,
        ]);

        if ($created) {
            return true;
        }

        throw new AppException(AppException::ACTION_FAILED);
    }

    public function leave ()
    {
        $politicalParty = App::user()->getPoliticalParty();

        if (!$politicalParty) {
            throw new AppException(AppException::ACTION_FAILED);
        }

        // check if he's the party creator
        $party = PartyModel::where([
            "id" => $politicalParty->id
        ]);

        // if he's the owner, remove the entire party and their affiliates
        if ($party->uid == App::user()->getUid())
        {
            PartyMember::where([
                "party" => $party->id
            ])->delete();

            $party->delete();
        } else {
            $politicalParty->delete();
        }


        return true;
    }

    public function showParty ($id)
    {
        if ($id < 1) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $party = PartyModel::where([
            "id" => $id
        ])->first();

        if (!$party) {
            throw new AppException(AppException::INVALID_DATA);
        }
        $party = $party->toArray();

        // check user affiliation
        $myAffiliation = App::user()->getPoliticalParty();

        if ($myAffiliation) {
            $party["affiliation"] = $myAffiliation->toArray();
        }

        return $this->render('party/profile.html.twig', [
            "party" => $party,
        ]);
    }

    public function showList ()
    {
        $list = PartyModel::where([
            "country" => App::user()->getLocation()["country"]["id"]
        ])->get()->toArray();

        return $this->render('party/list.html.twig', [
            "list" => $list,
        ]);
    }

    public function showCreationForm ()
    {
        return $this->render('party/create.html.twig', [
            "creationCost" => PartyModel::CREATION_COST
        ]);
    }

    /**
     * @ToDo: Make it free for the first 5 parties of each country
     * @throws AppException
     */
    public function create ()
    {
        $name = Utils::sanitizeString($_POST["name"], true);
        $description = Utils::sanitizeString($_POST["description"], true);
        $uid = App::user()->getUid();

        if (empty($name) || empty($description) || strlen($name) < 5 || strlen($description) < 5) {
            throw new AppException(AppException::INVALID_DATA);
        }

        // check if user can pay the fee
        $userMoney = Money::where([
            "uid" => $uid
        ])->first();

        if ($userMoney->gold < PartyModel::CREATION_COST) {
            throw new AppException(AppException::NO_ENOUGH_MONEY);
        }

        $created = PartyModel::create([
            "name" => $name,
            "description" => $description,
            "country" => App::user()->getLocation()["country"]["id"],
            "uid" => $uid,
        ]);

        if (!$created) {
            return false;
        }

        PartyMember::create([
            "uid" => $uid,
            "party" => $created->id,
            "level" => PartyMember::LEVEL_LEADER
        ]);

        $userMoney->gold -= PartyModel::CREATION_COST;
        $userMoney->save();

        return $created->id;
    }
}