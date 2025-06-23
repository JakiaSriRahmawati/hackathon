<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id', 'caption', 'todo_id', 'goal_id', 'image_url', 'visibility'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }
}
