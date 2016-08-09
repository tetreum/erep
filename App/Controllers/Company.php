<?php

namespace App\Controllers;

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
}