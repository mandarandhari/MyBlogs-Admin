<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['user_id', 'title', 'url', 'description', 'is_premium', 'content', 'meta_title', 'meta_description', 'banner', 'thumb'];
}
