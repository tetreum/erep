<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawProposal extends Model {

    protected $fillable = ["uid", "type", "country", "reason", "target_country", "amount", "yes", "no", "expected_votes", "finished"];
}
