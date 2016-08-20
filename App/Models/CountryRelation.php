<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryRelation extends Model {
    protected $fillable = ["country", "target", "relation"];
}