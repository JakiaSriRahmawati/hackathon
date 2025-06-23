<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignTodo extends Model
{
    protected $fillable = ['campaign_id', 'title', 'description', 'deadline'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function userTodos()
    {
        return $this->hasMany(UserCampaignTodo::class);
    }
}
