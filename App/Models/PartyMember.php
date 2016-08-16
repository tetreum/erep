<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartyMember extends Model
{
    const LEVEL_AFFILIATED = 1;
    const LEVEL_TODO = 2;
    const LEVEL_LEADER = 3;

    protected $fillable = ["party", "uid", "level"];
}