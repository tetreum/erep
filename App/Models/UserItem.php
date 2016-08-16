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
        if (empty($id)) {
            $id = $this->id;
        }

        if (empty($id)) {
            return false;
        }

        if (in_array($id, Item::where([
            "type" => Item::TYPE_RAW
        ]))) {
            return true;
        }
        return false;
    }
}