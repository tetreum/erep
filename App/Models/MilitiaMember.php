<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilitiaMember extends Model
{
    const RANK_OWNER = 1;
    const RANK_NEWBIE = 2;

    protected $fillable = ["militia", "uid", "rank"];
}