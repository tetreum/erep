<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    protected $table = "money";

    protected $fillable = ["uid", "gold"];

    public $timestamps = false;
}