<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = ['user_id', 'friend_id', 'status'];

    public function requester() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
