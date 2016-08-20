<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CongressCandidate extends Model {
    protected $fillable = ["uid", "yes", "no", "country"];

    const VOTE_DATE_LIMIT = 60 * 60 * 24 * 2; // days
}