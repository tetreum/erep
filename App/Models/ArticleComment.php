<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    const CHANNEL_TYPE_ARTICLE = 1;
    const CHANNEL_TYPE_REPLY = 2;

    public static $validTypes = [
        self::CHANNEL_TYPE_ARTICLE,
        self::CHANNEL_TYPE_REPLY
    ];

    protected $fillable = ["message", "sender", "channel_id", "channel_type", "likes"];
}