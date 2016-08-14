<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * @package App\Models
 *
 * This class has 'id' field because Eloquent doesn't support composite keys...
 */

class UserItem extends Model
{
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