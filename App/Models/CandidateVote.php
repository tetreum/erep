<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateVote extends Model {
    protected $fillable = ["candidate", "uid"];

    protected $timestamps = false;
}
