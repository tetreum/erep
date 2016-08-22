<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatVote extends Model
{
    protected $fillable = ["message", "voter"];
}