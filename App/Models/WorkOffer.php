<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOffer extends Model
{
    protected $fillable = ["company", "salary", "country", "worker", "last_work"];

    public function hasWorkedToday ()
    {
        if (!isset($this->company) || empty($this->company)) {
            return false;
        }

        $lastWorkTime = date("Y-m-d", strtotime($this->last_work));

        if ($lastWorkTime == date("Y-m-d")) {
            return true;
        }
        return false;
    }
}