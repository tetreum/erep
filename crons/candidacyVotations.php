<?php

require "../bootstrap.php";

use \App\Models\CongressCandidate;
use \App\Models\CongressMember;
use \App\Models\CandidateVote;

// THIS CRON FINISHES OLD CANDIDACY VOTATIONS

$candidacies = CongressCandidate::where([
    "created_at", "<=", ""
])->get();

foreach ($candidacies as $candidacy)
{
    if ($candidacy->yes > $candidacy->no) {
        CongressMember::create([
            "party" => $party->id,
            "uid" => $candidacy->uid,
            "country" => $party->country
        ]);
    }

    CongressCandidate::where([
        "uid" => $candidacy->uid
    ])->delete();

    CandidateVote::where([
        "candidate" => $candidacy->uid
    ])->delete();
}