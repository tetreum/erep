<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Chat messages should be deleted by a cron after 7 days as they are ephemeral
 **/
class Chat extends Model
{
    const CHANNEL_TYPE_WORLD = 1;
    const CHANNEL_TYPE_COUNTRY = 2;
    const CHANNEL_TYPE_POLITICAL_PARTY = 3;
    const CHANNEL_TYPE_MILITIA = 4;
    const CHANNEL_TYPE_REPLY = 5;

    public static $validTypes = [
        self::CHANNEL_TYPE_WORLD,
        self::CHANNEL_TYPE_COUNTRY,
        self::CHANNEL_TYPE_POLITICAL_PARTY,
        self::CHANNEL_TYPE_MILITIA,
        self::CHANNEL_TYPE_REPLY
    ];

    protected $fillable = ["message", "sender", "channel_id", "channel_type", "likes"];

    public function sender() {
        return $this->hasOne('App\Models\User', 'id', 'sender');
    }
}