<?php

namespace App\Controllers;

use App\Models\UserGym;
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
        $job = App::user()->getJob();

        $jobData = [
            "hasJob" => false,
            "hasWorkedToday" => false
        ];

        if (!empty($job)) {
            $jobData["hasJob"] = true;
            $jobData["hasWorkedToday"] = $job->hasWorkedToday();
        }

        $userGyms = UserGym::where([
            "uid" => App::user()->getUid()
        ])->first();


        return $this->render('home.html.twig', [
            "job" => $jobData,
            "hasTrainedToday" => $userGyms->hasTrainedToday(1)
        ]);
    }
}