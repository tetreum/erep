<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryFunds extends Model {
    protected $fillable = ["country", "gold", "esp"];

    protected $primaryKey = "country";
    protected $timestamps = false;
}