<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ["id", "uid", "type", "quality", "last_work"];

    public function hasManagerWorkedToday ()
    {
        if (!isset($this->uid) || empty($this->uid)) {
            return false;
        }

        $lastWorkTime = date("Y-m-d", strtotime($this->last_work));

        if ($lastWorkTime == date("Y-m-d")) {
            return true;
        }
        return false;
    }

    public function toArray ()
    {
        $data = parent::toArray();
        $data["hasManagerWorkedToday"] = $this->hasManagerWorkedToday();

        return $data;
    }
}