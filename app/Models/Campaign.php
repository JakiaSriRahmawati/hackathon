<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['creator_id', 'title', 'description', 'start_date', 'end_date'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function participants()
    {
        return $this->hasMany(CampaignParticipant::class);
    }

    public function todos()
    {
        return $this->hasMany(CampaignTodo::class);
    }
}
