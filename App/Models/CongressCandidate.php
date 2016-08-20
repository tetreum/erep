<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CongressCandidate extends Model
{
    const VOTE_DATE_LIMIT = 60 * 60 * 24 * 2; // days

    protected $primaryKey = "uid";

    protected $fillable = ["uid", "yes", "no", "country"];
}