<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'start_date', 'end_date', 'current_streak', 'is_active'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function progress()
    {
        return $this->hasMany(GoalProgress::class);
    }
}
