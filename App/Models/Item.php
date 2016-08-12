<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    const RAW_FOOD = 1;
    const RAW_WEAPON = 2;
    const RAW_HOUSE = 3;
    const FOOD = 4;
    const GUN = 5;
    const TANK = 6;

    protected $primaryKey = 'uid';
    protected $fillable = ["uid", "item", "quality", "quantity"];
    public $timestamps = false;

    public function isRaw ($id = null)
    {
        $rawItems = [self::RAW_FOOD, self::RAW_HOUSE, self::RAW_WEAPON];

        if (empty($id)) {
            $id = $this->id;
        }

        if (empty($id)) {
            return false;
        }

        if (in_array($id, $rawItems)) {
            return true;
        }
        return false;
    }
}