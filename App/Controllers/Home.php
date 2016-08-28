<?php

namespace App\Controllers;

use App\Models\NewspaperArticle;
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

        $latestArticles = NewspaperArticle::where([
            "country" => App::user()->getLocation()["country"]["id"]
        ])->limit(5)->get();

        $hasPoliticalParty = false;

        if (App::user()->getPoliticalParty()) {
            $hasPoliticalParty = true;
        }


        return $this->render('home.html.twig', [
            "job" => $jobData,
            "hasTrainedToday" => $userGyms->hasTrainedToday(1),
            "hasPoliticalParty" => $hasPoliticalParty,
            "latestArticles" => $latestArticles
        ]);
    }
}