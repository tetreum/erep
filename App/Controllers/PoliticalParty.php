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
    public function showParty ($id)
    {
        $party = PartyModel::where([
            "id" => $id
        ])->first()->toArray();

        if (!$party) {
            throw new AppException(AppException::INVALID_DATA);
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