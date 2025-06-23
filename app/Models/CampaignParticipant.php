<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignParticipant extends Model
{
    protected $fillable = ['campaign_id', 'user_id', 'joined_at'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
