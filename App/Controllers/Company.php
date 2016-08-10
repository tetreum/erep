<?php

namespace App\Controllers;

use App\Models\CompanyType;
use App\System\App;
use App\System\AppException;
use App\System\Controller;
use \App\Models\Company as CompanyModel;

class Company extends Controller
{
    public function showMyCompanies ()
    {
        $list = CompanyModel::where([
            "owner" => App::session()->getUid()
        ]);

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
}