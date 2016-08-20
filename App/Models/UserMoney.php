<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMoney extends Model
{
    protected $table = "user_money";
    protected $primaryKey = 'uid';

    protected $fillable = ["uid", "gold"];

    public $timestamps = false;
}