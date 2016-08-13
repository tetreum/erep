<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOffer extends Model
{
    protected $fillable = ["item", "uid", "price", "quantity", "quality", "country"];

}