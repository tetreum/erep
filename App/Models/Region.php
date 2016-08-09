<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ["name", "country"];

    public static function getFullInfo ($id)
    {
        return Region::with('country')->where('id', $id)->first()->toArray();
    }

    public function country() {
        return $this->hasOne('App\Models\Country', 'id', 'country');
    }
}