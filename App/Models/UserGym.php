<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGym extends Model
{
    protected $primaryKey = "uid";
    protected $fillable = ["uid", "q1", "q2", "q3", "q4"];

    public $timestamps = false;

    public static $data = [
        "q1" => [
            "cost" => 0,
            "energy" => 10,
            "strength" => 5,
        ],
        "q2" => [
            "cost" => 5,
            "energy" => 15,
            "strength" => 10,
        ],
        "q3" => [
            "cost" => 10,
            "energy" => 20,
            "strength" => 15,
        ],
        "q4" => [
            "cost" => 15,
            "energy" => 25,
            "strength" => 20,
        ]
    ];

    public function hasTrainedToday ($quality)
    {
        $lastTime = date("Y-m-d", strtotime($this["q$quality"]));

        if ($lastTime == date("Y-m-d")) {
            return true;
        }

        return false;
    }
}