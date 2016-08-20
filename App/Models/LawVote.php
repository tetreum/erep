<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawVote extends Model {

    protected $fillable = ["law", "uid", "in_favor"];
}