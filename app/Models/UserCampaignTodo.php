<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCampaignTodo extends Model
{
    protected $fillable = ['user_id', 'campaign_todo_id', 'is_done', 'completed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaignTodo()
    {
        return $this->belongsTo(CampaignTodo::class);
    }
}
