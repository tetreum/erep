<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Militia extends Model
{
    const CREATION_COST = 10; //gold
    protected $fillable = ["name", "country", "uid", "description"];
}