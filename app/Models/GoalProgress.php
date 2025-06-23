<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalProgress extends Model
{
    protected $fillable = ['goal_id', 'date', 'is_done'];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
