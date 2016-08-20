<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CongressMember extends Model {
    protected $fillable = ["party", "uid", "country"];
}