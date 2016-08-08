<?php

namespace App\Controllers;

use App\Models\WorkOffer;
use App\System\App;
use App\System\AppException;
use App\System\Controller;

class Home extends Controller
{
    /**
     * Show home page
     * @return mixed
     */
    public function showHomepage ()
    {
        if (!$this->isLogged) {
            App::redirect($this->app->getContainer()->get('router')->pathFor('home'));
            exit;
        }

        /**
         * Check if user has job and has worked today
         */
        $job = WorkOffer::where([
            "worker" => App::session()->getUid()
        ])->first();

        $jobData = [
            "hasJob" => false,
            "hasWorkedToday" => false
        ];

        if (!empty($job)) {
            $jobData["hasJob"] = true;

            $lastWorkTime = date("Y-m-d", strtotime($job["last_work"]));

            if ($lastWorkTime == date("Y-m-d")) {
                $jobData["hasWorkedToday"] = true;
            }
        }

        return $this->render('home.html.twig', [
            "job" => $jobData
        ]);
    }
}