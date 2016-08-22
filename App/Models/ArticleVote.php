<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleVote extends Model {
    protected $fillable = ["article", "uid"];
}