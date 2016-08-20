<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryRelation extends Model
{
    const RELATION_ALLY = "ally";
    const RELATION_ENEMY = "enemy";

    protected $fillable = ["country", "target", "relation"];
}