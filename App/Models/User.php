<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const STATUS_BANNED = 2;
    const STATUS_ACTIVATED = 1;
    const STATUS_PENDING = 0;
    const MIN_PASSWORD_LENGTH = 7;

    protected $fillable = ["email", "nick", "password", "status", "country", "referrer", "xp", "level"];

}