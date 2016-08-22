<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateMessage extends Model {
    protected $fillable = ["sender", "receiver", "message"];
}