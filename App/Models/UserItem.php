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
}