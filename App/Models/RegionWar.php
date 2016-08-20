<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionWar extends Model
{
    protected $fillable = ["region", "attacker", "defender"];

    protected $primaryKey = "region";
}