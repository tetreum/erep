<?php

require "../bootstrap.php";

use \App\Models\LawProposal;
use \App\Controllers\Congress;

// THIS CRON FINISHES LAW PROPOSALS

$proposals = LawProposal::where([
    "finished" => false,
    "created_at", "<=", ""
]);

foreach ($proposals as $proposal)
{
    if ($proposal->yes > $proposal->no) {
        $congress = new Congress();
        $congress->applyLaw($proposal->id);
    }

    $proposal->finished = true;
    $proposal->save();
}