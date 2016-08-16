<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoliticalParty extends Model
{
    const CREATION_COST = 5;

    protected $fillable = ["uid", "name", "description", "country"];
}