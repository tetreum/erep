<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCommentVote extends Model
{
    protected $fillable = ["message", "voter"];
}