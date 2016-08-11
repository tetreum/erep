<?php

namespace App\Controllers;

use App\Models\CompanyType;
use App\System\App;
use App\System\AppException;
use App\System\Controller;
use \App\Models\Company as CompanyModel;
use App\System\Session;

class Company extends Controller
{
    public function showMyCompanies ()
    {
        $list = CompanyModel::where([
            "uid" => App::session()->getUid()
        ])->get()->toArray();

        return $this->render('user/companies.html.twig', [
            "companies" => $list,
        ]);
    }

    public function showCreate ()
    {
        $list = [];

        // reorder them by sector
        foreach (CompanyType::$types as $company) {
            $list[$company["sector"]][] = $company;
        }


        return $this->render('user/create_company.html.twig', [
            "sectors" => $list,
        ]);
    }

    public function create ()
    {
        $id = (int)$_POST["id"];
        $quality = (int)$_POST["quality"];

        if ($id < 1 || $quality < 1) {
            throw new AppException(AppException::INVALID_DATA);
        }

        try {
            $companyDetails = CompanyType::getInfo($id, $quality);

            if (empty($companyDetails)) {
                throw new AppException(AppException::INVALID_DATA);
            }
        } catch (\Exception $e) {
            throw new AppException(AppException::INVALID_DATA);
        }

        if (!App::user()->buy($companyDetails["price"], $companyDetails["currency"], Session::PURCHASE_TYPE_COMPANY)) {
            throw new AppException(AppException::NO_ENOUGH_MONEY);
        }

        $created = CompanyModel::create([
            "uid" => App::user()->getUid(),
            "type" => $id,
            "quality" => $quality,
        ]);

        if ($created) {
            return true;
        }
        return false;
    }
}