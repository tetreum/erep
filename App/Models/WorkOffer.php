<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOffer extends Model
{
    protected $fillable = ["company", "salary", "country"];
}