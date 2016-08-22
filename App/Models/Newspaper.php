<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newspaper extends Model {
    const CREATION_COST = 5; //gold
    protected $fillable = ["name", "country", "uid", "description"];
}