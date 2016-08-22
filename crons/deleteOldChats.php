<?php

require "../bootstrap.php";

use \App\Models\Chat;

/**
THIS CRONJOB DELETES OLD CHAT MESSAGES

RUN IT DAILY
 **/

$aWeekAgo = date("Y-m-d", strtotime("-1 week"));

Chat::where([
    "created_at", ">", $aWeekAgo
])->delete();