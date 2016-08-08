<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ["name", "country"];

    public static function getFullInfo ($id)
    {
        $regionModel = self::find($id);
        $region = $regionModel->toArray();

        $region["country"] = $regionModel->countryInfo->toArray();

        return $region;
    }

    public function countryInfo() {
        return $this->hasOne('App\Models\Country', 'id', 'country');
    }
}